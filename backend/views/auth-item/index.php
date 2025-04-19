<?php

use common\models\AuthItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\AuthItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rollar';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="page">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbs['item'] as $item) : ?>
                <li class='breadcrumb-item'>
                    <?= Html::a($item['label'], $item['url'], ['class' => '']) ?>
                </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <p class="mb-3 mt-4">
        <?= Html::a('Qo\'shish', ['create'], ['class' => 'b-btn b-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
               'attribute' => 'name',
               'contentOptions' => ['date-label' => 'name'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->name;
               },
            ],
            [
               'attribute' => 'description',
               'contentOptions' => ['date-label' => 'description'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->description;
               },
            ],
            [
                'attribute' => 'Ruxsatlar',
                'contentOptions' => ['date-label' => 'Ruxsatlar'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<a href='". Url::to(['actions/permission' , 'role' => $model->name]) ."' class='badge-table-div active'><span>Ruxsatlar</span></a>";
                },
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'view'   => function () {
                        return false;
                    },
                    'update' => function ($url, $model) {
                        $url = Url::to(['update', 'name' => $model->name]);
                        return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                            'title' => 'update',
                            'class' => 'tableIcon',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['delete', 'name' => $model->name]);
                        return Html::a('<i class="fa fa-trash"></i>', $url, [
                            'title' => 'delete',
                            'class' => 'tableIcon',
                            'data-confirm' => Yii::t('yii', 'Ma\'lumotni o\'chirishni xoxlaysizmi?'),
                            'data-method'  => 'post',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
