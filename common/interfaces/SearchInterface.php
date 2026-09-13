<?php

namespace common\interfaces;

use yii\data\BaseDataProvider;

interface SearchInterface
{
    public function search(): BaseDataProvider;
}