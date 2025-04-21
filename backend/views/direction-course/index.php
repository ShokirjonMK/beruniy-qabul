<?php

use common\models\DirectionCourse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;



/** @var yii\web\View $this */
/** @var common\models\DirectionCourseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\EduDirection $eduDirection */

$this->title = 'Bosqich qo\'shish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Ta\'lim yo\'nalishlari'),
    'url' => ['edu-direction/index'],
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

    <?php if (permission('direction-course', 'create')): ?>
        <p class="mb-3 mt-4">
            <?= Html::a('Qo\'shish', ['create', 'id' => $eduDirection->id], ['class' => 'b-btn b-primary']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
               'attribute' => 'course_id',
               'contentOptions' => ['date-label' => 'course_id'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->course->name_uz;
               },
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['date-label' => 'status'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<div class='badge-table-div active'><span>".Status::accessStatus($model->status)."</span></div>";
                },
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'view'   => function ($url, $model) {
                        if (permission('direction-course', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        if (permission('direction-course', 'update')) {
                            $url = Url::to(['update', 'id' => $model->id]);
                            return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                                'title' => 'update',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('direction-course', 'delete')) {
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
