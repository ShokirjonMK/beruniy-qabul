<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Status;
use common\models\Branch;

/** @var yii\web\View $this */
/** @var common\models\Consulting $model */

$this->title = 'Ko\'rish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Hamkorlar'),
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
        <?php if (permission('constalting', 'update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary mb-3']) ?>
        <?php endif; ?>

        <?php if (permission('constalting', 'delete')): ?>
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
            'name',
            'hr',
            'bank_name_uz',
            'bank_name_ru',
            'bank_name_en',
            'bank_adress_uz',
            'bank_adress_ru',
            'bank_adress_en',
            'mfo',
            'inn',
            'tel1',
            'tel2',
            'domen',
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
