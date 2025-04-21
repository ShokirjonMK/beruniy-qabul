<?php

use common\models\ExamDate;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;
use common\models\Student;

/** @var yii\web\View $this */
/** @var common\models\ExamDateSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Imtixon sanalari';
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

    <?php if (permission('exam-date', 'create')): ?>
        <p class="mb-3 mt-4">
            <?= Html::a('Qo\'shish', ['create'], ['class' => 'b-btn b-primary']) ?>
        </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'branch_id',
                'contentOptions' => ['date-label' => 'branch_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->branch->name_uz;
                },
            ],
            [
               'attribute' => 'date',
               'contentOptions' => ['date-label' => 'date'],
               'format' => 'raw',
               'value' => function($model) {
                   return "<div class='badge-table-div active'>".date("Y-m-d H:i" , strtotime($model->date))."</div>";
               },
            ],
            [
                'attribute' => 'limit',
                'contentOptions' => ['date-label' => 'limit'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->limit;
                },
            ],
            [
                'attribute' => 'Arizalar soni',
                'contentOptions' => ['date-label' => 'Arizalar soni'],
                'format' => 'raw',
                'value' => function($model) {
                    return Student::find()
                        ->alias('s')
                        ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
                        ->where([
                            'u.status' => [9, 10],
                            'u.user_role' => 'student',
                            's.exam_date_id' => $model->id,
                        ])->count();
                },
            ],
            [
               'attribute' => 'status',
               'contentOptions' => ['date-label' => 'status'],
               'format' => 'raw',
               'value' => function($model) {
                    if ($model->status == 1) {
                        return "<div class='badge-table-div active'>".Status::accessStatus($model->status)."</div>";
                    }
                   return "<div class='badge-table-div danger'>".Status::accessStatus($model->status)."</div>";
               },
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'view'   => function ($url, $model) {
                        if (permission('exam-date', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        if (permission('exam-date', 'update')) {
                            $url = Url::to(['update', 'id' => $model->id]);
                            return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                                'title' => 'update',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('exam-date', 'delete')) {
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
