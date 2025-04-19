<?php

use common\models\CrmPush;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Student;

/** @var yii\web\View $this */
/** @var common\models\CrmPushSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'AMO';
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

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount ?></b></p>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
               'attribute' => 'student_id',
               'contentOptions' => ['date-label' => 'student_id'],
               'format' => 'raw',
               'value' => function($model) {
                    $student = Student::findOne($model->student_id);
                   return $student->fullName." | ".$student->username ?? null;
               },
            ],
            [
               'attribute' => 'type',
               'contentOptions' => ['date-label' => 'type'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->type;
               },
            ],
            [
               'attribute' => 'push_time',
               'contentOptions' => ['date-label' => 'push_time'],
               'format' => 'raw',
               'value' => function($model) {
                   return date("Y-m-d H:i:s" , $model->push_time);
               },
            ],
            [
                'attribute' => 'pipeline_id',
                'contentOptions' => ['date-label' => 'pipeline_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->pipeline_id;
                },
            ],
            [
               'attribute' => 'lead_id',
               'contentOptions' => ['date-label' => 'lead_id'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->lead_id;
               },
            ],
            [
                'attribute' => 'lead_status',
                'contentOptions' => ['date-label' => 'lead_status'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->lead_status;
                },
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['date-label' => 'status'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->status;
                },
            ],
            [
                'attribute' => 'is_deleted',
                'contentOptions' => ['date-label' => 'is_deleted'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->is_deleted;
                },
            ],
            [
                'attribute' => 'data',
                'contentOptions' => ['date-label' => 'data'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->data;
                },
            ],
        ],
    ]); ?>


</div>
