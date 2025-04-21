<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Status;

/** @var yii\web\View $this */
/** @var common\models\Direction $model */

$this->title = 'Ko\'rish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Yo\'nalishlar'),
    'url' => ['index'],
];
\yii\web\YiiAsset::register($this);
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

    <p class="mb-3">
        <?php if (permission('direction', 'update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary mb-3']) ?>
        <?php endif; ?>

        <?php if (permission('direction', 'delete')): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'b-btn b-danger mb-3',
                'data' => [
                    'confirm' => Yii::t('app', 'Ma\'lumotni o\'chirishni xoxlaysizmi?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="grid-view">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'branch_id',
                    'contentOptions' => ['date-label' => 'branch_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->branch->name_uz ?? null;
                    },
                ],
            'name_uz',
            'name_ru',
            'name_en',
            'code',
                [
                    'attribute' => 'status',
                    'contentOptions' => ['date-label' => 'status'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return "<div class='badge-table-div active'><span>".Status::accessStatus($model->status)."</span></div>";
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'contentOptions' => ['date-label' => 'created_at'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return date('Y-m-d H:i:s' , $model->created_at);
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'contentOptions' => ['date-label' => 'updated_at'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return date('Y-m-d H:i:s' , $model->updated_at);
                    },
                ],
                [
                    'attribute' => 'created_by',
                    'contentOptions' => ['date-label' => 'created_by'],
                    'format' => 'raw',
                    'value' => function($model) {
                        $emp = $model->createdBy->employee ?? null;
                        if ($emp) {
                            return $emp->first_name . ' ' . $emp->last_name;
                        }
                        return '<span class="text-muted">Not Available</span>';
                    },
                ],
                [
                    'attribute' => 'updated_by',
                    'contentOptions' => ['date-label' => 'updated_by'],
                    'format' => 'raw',
                    'value' => function($model) {
                        $emp = $model->updatedBy->employee ?? null;
                        if ($emp) {
                            return $emp->first_name . ' ' . $emp->last_name;
                        }
                        return '<span class="text-muted">Not Available</span>';
                    },
                ],
            ],
        ]) ?>
    </div>

</div>
