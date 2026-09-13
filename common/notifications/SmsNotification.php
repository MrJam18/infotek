<?php

namespace common\notifications;

use common\notifications\base\AbstractNotification;
use yii\base\InvalidArgumentException;

class SmsNotification extends AbstractNotification
{
    use CheckSmsTrait;

    public function setRecipient(string $recipient): void
    {
        if (!preg_match($this->getSmsPattern(), $recipient)) {
            throw new InvalidArgumentException('Recipient is not valid');
        }
        $this->recipient = $recipient;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}