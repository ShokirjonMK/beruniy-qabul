<?php

use common\models\AuthItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;

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

    <?php if (permission('auth-item', 'create')): ?>
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
               'attribute' => 'name',
               'contentOptions' => ['date-label' => 'name'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->name;
               },
            ],
            [
                'attribute' => 'type',
                'contentOptions' => ['date-label' => 'type'],
                'format' => 'raw',
                'value' => function($model) {
                    return Status::rolType($model->type);
                },
            ],
            [
                'attribute' => 'branch_id',
                'contentOptions' => ['date-label' => 'branch_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->branch->name_uz ?? null;
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
                    if (permission('actions', 'permission')) {
                        return "<a href='". Url::to(['actions/permission' , 'role' => $model->name]) ."' class='badge-table-div active'><span>Ruxsatlar</span></a>";
                    }
                },
            ],
            [
                'attribute' => 'Harakatlar',
                'contentOptions' => ['date-label' => 'Harakatlar'],
                'format' => 'raw',
                'value' => function($model) {
                    if (permission('auth-item', 'update')) {
                        return "<a href='". Url::to(['update' , 'name' => $model->name]) ."' class='badge-table-div active'><span>Tahrirlash</span></a>";
                    }
                },
            ],
        ],
    ]); ?>


</div>
