<?php

namespace common\notifications\base;

use common\models\Book;
use common\models\Subscription;

interface BookNotifierInterface
{
    public function notify(Book $book): void;
}