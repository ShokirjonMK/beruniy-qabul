<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Status;

/** @var yii\web\View $this */
/** @var common\models\EduDirection $model */

$this->title = 'Ko\'rish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'E\'lon qilingan yo\'nalishlar'),
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
        <?php if (permission('edu-direction', 'update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary']) ?>
        <?php endif; ?>

        <?php if (permission('edu-direction', 'delete')): ?>
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
                    'attribute' => 'branch_id',
                    'contentOptions' => ['date-label' => 'branch_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->branch->name_uz ?? null;
                    },
                ],
                [
                    'attribute' => 'direction_id',
                    'contentOptions' => ['date-label' => 'direction_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        $direction = $model->direction;
                        $data = '';
                        if ($model->edu_type_id == 1) {
                            if ($model->exam_type != null) {
                                $exams = json_decode($model->exam_type, true);
                                if (count($exams) > 0) {
                                    $data .= '<br>';
                                    foreach ($exams as $exam) {
                                        $data .= "<div class='badge-table-div active mt-2'><span>".Status::getExamStatus($exam)."</span></div> &nbsp;";
                                    }
                                }
                            }
                        }
                        return $direction->code." - ".$direction->name_uz. " ".$data;
                    },
                ],
                [
                    'attribute' => 'lang_id',
                    'contentOptions' => ['date-label' => 'lang_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->lang->name_uz;
                    },
                ],
                [
                    'attribute' => 'edu_type_id',
                    'contentOptions' => ['date-label' => 'edu_type_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->eduType->name_uz;
                    },
                ],
                [
                    'attribute' => 'edu_form_id',
                    'contentOptions' => ['date-label' => 'edu_form_id'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->eduForm->name_uz;
                    },
                ],
                'duration',
                'price',
                'is_oferta',
                [
                    'attribute' => 'is_oferta',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return "<div class='badge-table-div active'>".Status::ofertaStatus($model->is_oferta)."</div>";
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 1) {
                            return "<span class='badge-table-div active'>Faol</span>";
                        } else {
                            return "<span class='badge-table-div danger'>No faol</span>";
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
