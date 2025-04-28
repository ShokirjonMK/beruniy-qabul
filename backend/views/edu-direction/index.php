<?php

use common\models\EduDirection;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Direction;
use common\models\Lang;
use common\models\EduType;
use common\models\EduForm;
use common\models\Status;
use common\models\DirectionSubject;

/** @var yii\web\View $this */
/** @var common\models\EduDirectionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'E\'lon qilingan yo\'nalishlar';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];

$directions = Direction::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$langs = Lang::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduTypes = EduType::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduForms = EduForm::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
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

    <?php if (permission('edu-direction', 'create')): ?>
        <p class="mb-3 mt-4">
            <?= Html::a('Qo\'shish', ['create'], ['class' => 'b-btn b-primary']) ?>
        </p>
    <?php endif; ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
               'filter' => Html::activeDropDownList($searchModel, 'direction_id',
                   ArrayHelper::map($directions, 'id', 'name'),
                   ['class'=>'form-control','prompt' => 'Yo\'nalish tanlang ...']),
            ],
            [
               'attribute' => 'lang_id',
               'contentOptions' => ['date-label' => 'lang_id'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->lang->name_uz;
               },
               'filter' => Html::activeDropDownList($searchModel, 'lang_id',
                    ArrayHelper::map($langs, 'id', 'name_uz'),
                    ['class'=>'form-control','prompt' => 'Til tanlang ...']),
            ],
            [
                'attribute' => 'edu_type_id',
                'contentOptions' => ['date-label' => 'edu_type_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->eduType->name_uz;
                },
                'filter' => Html::activeDropDownList($searchModel, 'edu_type_id',
                    ArrayHelper::map($eduTypes, 'id', 'name_uz'),
                    ['class'=>'form-control','prompt' => 'Ta\'lim turini tanlang ...']),
            ],
            [
                'attribute' => 'edu_form_id',
                'contentOptions' => ['date-label' => 'edu_form_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->eduForm->name_uz;
                },
                'filter' => Html::activeDropDownList($searchModel, 'edu_form_id',
                    ArrayHelper::map($eduForms, 'id', 'name_uz'),
                    ['class'=>'form-control','prompt' => 'Ta\'lim shaklini tanlang ...']),
            ],
            [
               'attribute' => 'duration',
               'contentOptions' => ['date-label' => 'duration'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->duration;
               },
            ],
            [
               'attribute' => 'price',
               'contentOptions' => ['date-label' => 'price'],
               'format' => 'raw',
               'value' => function($model) {
                   return $model->price;
               },
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['date-label' => 'status'],
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->status == 1) {
                        return "<div class='badge-table-div active'><span>Faol</span></div>";
                    } elseif ($model->status == 0) {
                        return "<div class='badge-table-div danger'><span>No faol</span></div>";
                    }
                }
            ],
            [
               'attribute' => 'Fanlar soni',
               'contentOptions' => ['date-label' => 'Fanlar soni'],
               'format' => 'raw',
               'value' => function($model) {
                if ($model->edu_type_id == 1) {
                    return DirectionSubject::find()
                    ->where(['is_deleted' => 0 , 'status' => 1, 'edu_direction_id' => $model->id])->count();
                }
               },
            ],
            [
                'attribute' => 'Item',
                'contentOptions' => ['data-label' => 'Item'],
                'format' => 'raw',
                'value' => function ($model) {
                    $labels = [
                        1 => ['url' => 'direction-subject/index', 'text' => 'Fanlar'],
                        2 => ['url' => 'direction-course/index', 'text' => 'Bosqichlar'],
                    ];

                    if (isset($labels[$model->edu_type_id])) {
                        $url = Url::to([$labels[$model->edu_type_id]['url'], 'id' => $model->id]);
                        $text = $labels[$model->edu_type_id]['text'];
                        return "<a href='{$url}' class='badge-table-div active'><span>{$text}</span></a>";
                    }

                    return null;
                },
            ],
            [
                'attribute' => 'Harakatlar',
                'contentOptions' => ['data-label' => 'Harakatlar'],
                'format' => 'raw',
                'value' => function ($model) {
                    $view = '';
                    if (permission('edu-direction', 'view')) {
                        $view = Html::a('Ko\'rish', Url::to(['view', 'id' => $model->id]), [
                            'title' => 'view',
                            'class' => 'badge-table-div active',
                        ])."<br>";
                    }
                    $update = '';
                    if (permission('edu-direction', 'update')) {
                        $update = Html::a('Tahrirlash', Url::to(['update', 'id' => $model->id]), [
                            'title' => 'update',
                            'class' => 'badge-table-div active mt-2',
                        ])."<br>";
                    }
                    $delete = '';
                    if (permission('edu-direction', 'delete')) {
                        $delete = Html::a('O\'chirish', Url::to(['delete', 'id' => $model->id]), [
                            'title' => 'delete',
                            'class' => 'badge-table-div danger mt-2',
                            'data-confirm' => Yii::t('yii', 'Ma\'lumotni o\'chirishni xoxlaysizmi?'),
                            'data-method'  => 'post',
                        ]);
                    }
                    return $view.$update.$delete;
                },
            ],
        ],
    ]); ?>


</div>
