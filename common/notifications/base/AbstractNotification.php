<?php

namespace common\notifications\base;

use yii\base\Component;

abstract class AbstractNotification extends Component
{

    protected string $recipient;

    protected string $message;

    abstract public function setRecipient(string $recipient): void;
    abstract public function setMessage(string $message): void;

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}