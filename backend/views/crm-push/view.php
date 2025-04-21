<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CrmPush $model */

$this->title = 'Ko\'rish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
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
        <?php if (permission('crm-push', 'update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'name' => $model->name], ['class' => 'b-btn b-primary mb-3']) ?>
        <?php endif; ?>

        <?php if (permission('crm-push', 'delete')): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'name' => $model->name], [
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
            'student_id',
            'type',
            'push_time:datetime',
            'lead_id',
            'lead_status',
            'data:ntext',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'is_deleted',
            ],
        ]) ?>
    </div>

</div>
