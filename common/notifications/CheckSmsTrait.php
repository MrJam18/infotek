<?php

namespace common\notifications;

trait CheckSmsTrait
{
    protected function getSmsPattern(): string
    {
        return '/^\+7?[0-9]{10,15}$/';
    }
}