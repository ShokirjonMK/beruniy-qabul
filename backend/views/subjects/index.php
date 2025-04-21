<?php

use common\models\Questions;
use common\models\Subject;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\SubjectsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Fanlar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];

?>
<div class="course-index">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumbs['item'] as $item) {
                echo "<li class='breadcrumb-item'><a href='". Url::to($item['url']) ."'>". $item['label'] ."</a></li>";
            }
            ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <?php if (permission('subjects', 'create')): ?>
        <div class="mb-3 mt-4">
            <?= Html::a(Yii::t('app', 'Qo\'shish'), ['create'], ['class' => 'b-btn b-primary']) ?>
        </div>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name_uz',
            'name_en',
            'name_ru',
            [
                'attribute' => 'language_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->language->name_uz;
                }
            ],
            [
                'attribute' => 'Tasdiqlangan savollar soni',
                'contentOptions' => ['date-label' => 'Tasdiqlangan savollar soni'],
                'format' => 'raw',
                'value' => function($model) {
                    $query = Questions::find()
                        ->where(['subject_id' => $model->id,'is_deleted' => 0, 'status' => 1, 'type' => 0])
                        ->count();
                    return "<div class='badge-table-div active'>".$query." ta</div>";
                },
            ],
            [
                'attribute' => 'Savollar',
                'contentOptions' => ['date-label' => 'Savollar'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<a href='". Url::to(['questions/index' , 'id' => $model->id]) ."' class='badge-table-div active'><span>Savollar</span></a>";
                },
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'entered' => function ($url, $model) {
                        if (permission('questions', 'index')) {
                            $url = Url::to(['questions/index', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-question"></i>', $url, [
                                'title' => 'Savollar',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'view'   => function ($url, $model) {
                        if (permission('subjects', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        if (permission('subjects', 'update')) {
                            $url = Url::to(['update', 'id' => $model->id]);
                            return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                                'title' => 'update',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('subjects', 'delete')) {
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
