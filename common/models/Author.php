<?php

declare(strict_types=1);

namespace common\models;

use common\models\queries\AuthorQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $full_name
 * @property int $books_count Virtual attribute populated by the report query
 * @property-read Book[] $books
 * @property-read Subscription[] $subscriptions
 */
class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author}}';
    }

    public function rules(): array
    {
        return [
            [['full_name'], 'required'],
            [['full_name'], 'trim'],
            [['full_name'], 'string', 'max' => 255],
        ];
    }

    public static function find(): AuthorQuery
    {
        return new AuthorQuery(static::class);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(Subscription::class, ['author_id' => 'id']);
    }
}
