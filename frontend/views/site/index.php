<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use common\models\Book;
use frontend\models\search\BookSearch;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Гость';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="guest-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'label' => 'Название',
            ],
            [
                'attribute' => 'year',
                'label' => 'Год',
            ],
            [
                'attribute' => 'isbn',
                'label' => 'ISBN',
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'photo',
                'label' => 'Фото',
                'format' => 'raw',
                'value' => static fn ($model) => $model->photo
                    ? Html::img('/' . $model->photo, ['style' => 'max-width: 80px;'])
                    : null,
            ],
            [
                'attribute' => 'authors',
                'label' => 'Авторы',
                'format' => 'raw',
                'filter' => $searchModel->getAuthorsFilter(),
                'value' => function (Book $model) {
                    $links = [];
                    foreach ($model->authors as $author) {
                        $links[] = Html::a(Html::encode($author->full_name), ['subscribe', 'id' => $author->id]);
                    }
                    return implode(', <br>', $links);
                }
            ],
            [
                'attribute' => 'subscribe',
                'headerOptions' => [
                    'width' => '3%',
                ],
                'format' => 'raw',
                'value' => function (Book $model) {
                }
            ]
        ],
    ]) ?>
</div>