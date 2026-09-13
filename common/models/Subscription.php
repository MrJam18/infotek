<?php

declare(strict_types=1);

namespace common\models;

use console\jobs\NotifyBookSubscribersJob;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Guest subscription to an author's new books.
 *
 * @property int $id
 * @property int $guest_id
 * @property int $author_id
 * @property int $created_at
 * @property-read Guest $guest
 * @property-read Book $book
 */
class Subscription extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
                'value' => static fn (): int => time(),
            ],
        ];
    }

    public static function tableName(): string
    {
        return '{{%subscription}}';
    }

    public function getGuest(): ActiveQuery
    {
        return $this->hasOne(Guest::class, ['id' => 'guest_id']);
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * Observer of Book::EVENT_CREATED: queues an SMS notification job for the new book's authors.
     */
    public static function onBookCreated(Event $event): void
    {
        Yii::$app->queue->push(new NotifyBookSubscribersJob([
            'bookId' => $event->sender->id,
        ]));
    }
}
