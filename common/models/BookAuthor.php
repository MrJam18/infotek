<?php

namespace common\models;

use yii\db\ActiveRecord;

class BookAuthor extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book_author}}';
    }
}