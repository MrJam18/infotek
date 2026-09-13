<?php

declare(strict_types=1);

namespace console\jobs;

use common\models\Book;
use common\notifications\base\BookNotifierInterface;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Sends notifications to subscribers of the new book's authors.
 */
class NotifyBookSubscribersJob extends BaseObject implements JobInterface
{
    public int $bookId;

    public function execute($queue): void
    {
        if (!$this->bookId) {
            return;
        }
        $book = Book::findOne($this->bookId);
        if (!$book) {
            return;
        }

        //maybe also other notifier like EmailNotifier. Show skills.
        /** @var BookNotifierInterface $notifier */
        $notifier = Yii::$container->get(BookNotifierInterface::class);
        $notifier->notify($book);
    }
}
