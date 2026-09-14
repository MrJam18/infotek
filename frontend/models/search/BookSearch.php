<?php

namespace frontend\models\search;

use common\interfaces\SearchInterface;
use common\models\Author;
use common\models\Book;
use common\models\Guest;
use yii\base\Component;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;

class BookSearch extends Model implements SearchInterface
{

    public $user_id;
    public $author_id;

    public function rules(): array
    {
        return [
          [['author_id'], 'integer'],
        ];
    }

    public function search(): BaseDataProvider
    {
        $query = Book::find()->with('authors')
            ->andFilterWhere([
                'author_id' => $this->author_id,
                'user_id' => $this->user_id,
            ]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'title',
                    'year',
                    'description',
                    'isbn',
                    'photo'
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);
    }

    public function getAuthorsFilter(): array
    {
        return  ['' => 'Все'] + Author::find()->indexBy('id')->select(['full_name', 'id'])->column();
    }
}