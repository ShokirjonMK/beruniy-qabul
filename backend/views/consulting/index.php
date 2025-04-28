<?php

use common\models\Consulting;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ConsultingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Hamkorlar';
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

    <?php if (permission('consulting', 'create')): ?>
        <p class="mb-3 mt-4">
            <?= Html::a('Qo\'shish', ['create'], ['class' => 'b-btn b-primary']) ?>
        </p>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
               'attribute' => 'hr',
               'contentOptions' => ['date-label' => 'hr'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->hr;
               },
            ],
            [
                'attribute' => 'Domen',
                'contentOptions' => ['date-label' => 'Domen'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<a class='badge-table-div active' href='https://{$model->domen}'>".$model->domen."</a>";
                },
            ],

            [
                'attribute' => 'Chala arizalar',
                'contentOptions' => ['date-label' => 'Chala arizalar'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->chalaStudentsCount;
                },
            ],
            [
                'attribute' => 'To\'liq ro\'yhatdan o\'tganlar',
                'contentOptions' => ['date-label' => 'To\'liq ro\'yhatdan o\'tganlar'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->studentsCount;
                },
            ],
            [
                'attribute' => 'Shartnoma tasdiqlanganlar',
                'contentOptions' => ['date-label' => 'Shartnoma tasdiqlanganlar'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->contract;
                },
            ],

            [
                'attribute' => 'Shartnoma yuklab olganlar',
                'contentOptions' => ['date-label' => 'Shartnoma yuklab olganlar'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->contractLoad;
                },
            ],

            [
                'attribute' => 'Almashish',
                'contentOptions' => ['date-label' => 'Almashish'],
                'format' => 'raw',
                'value' => function($model) {
                    if (permission('consulting', 'replace')) {
                        return "<a href='". Url::to(['consulting/replace' , 'id' => $model->id]) ."' class='badge-table-div active'><span><i class='fa-solid fa-arrows-rotate p-0'></i></span></a>";
                    }
                },
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'view' => function ($url, $model) {
                        if (permission('consulting', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        if (permission('consulting', 'update')) {
                            $url = Url::to(['update', 'id' => $model->id]);
                            return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                                'title' => 'update',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('consulting', 'delete')) {
                            $url = Url::to(['delete', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-trash"></i>', $url, [
                                'title' => 'delete',
                                'class' => 'tableIcon',
                                'data-confirm' => Yii::t('yii', 'Ma\'lumotni o\'chirishni xoxlaysizmi?'),
                                'data-method'  => 'post',
                            ]);
                        }
                        return false;
                    },
                ],
            ],
        ],
    ]); ?>


</div>
