<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\DirectionCourse $model */

$this->title = 'Ko\'rish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Ta\'lim yo\'nalishlari'),
    'url' => ['edu-direction/index'],
];
$breadcrumbs['item'][] = [
    'label' => $model->eduDirection->direction->name.' yo\'nalish bosqichlari',
    'url' => ['index' , 'id' => $model->edu_direction_id],
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
        <?php if (permission('direction-course', 'update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary mb-3']) ?>
        <?php endif; ?>

        <?php if (permission('direction-course', 'delete')): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'b-btn b-danger',
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
                    'attribute' => 'course_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->course->name_uz;
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 1) {
                            return "<span class='editable editable-click'>Faol</span>";
                        } else {
                            return "<span class='editable editable-click editable-empty'>No faol</span>";
                        }
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s' , $model->created_at);
                    }
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s' , $model->updated_at);
                    }
                ],
                [
                    'attribute' => 'created_by',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->created_by == null || $model->created_by == 0) {
                            return 0;
                        }
                        $profile = $model->createdBy->employee;
                        return $profile->first_name . " " .$profile->last_name. " ". $profile->middle_name;
                    }
                ],
                [
                    'attribute' => 'updated_by',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->updated_by == null || $model->updated_by == 0) {
                            return 0;
                        }
                        $profile = $model->updatedBy->employee;
                        return $profile->first_name . " " .$profile->last_name. " ". $profile->middle_name;
                    }
                ],
            ],
        ]) ?>
    </div>

</div>
