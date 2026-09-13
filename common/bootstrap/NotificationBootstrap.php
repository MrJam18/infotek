<?php

declare(strict_types=1);

namespace common\bootstrap;

use common\models\Book;
use common\models\Subscription;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * Registers observers for domain events (Observer pattern).
 */
final class NotificationBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        Event::on(Book::class, Book::EVENT_CREATED, [Subscription::class, 'onBookCreated']);
    }
}
