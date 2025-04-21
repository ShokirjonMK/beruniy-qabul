<?php

use common\models\Questions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Subjects $subject */
/** @var common\models\QuestionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Savollar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => 'Fanlar ( '.$subject->name_uz . ' )',
    'url' => ['subjects/index' , 'id' => $subject->id],
];

$questions = $dataProvider->getModels();
?>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<div class="questions-index">

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

    <div class="mb-3 mt-4">
        <?php if (permission('questions', 'create')): ?>
            <?= Html::a(Yii::t('app', 'Savol qo\'shish'), ['create' , 'id' => $subject->id], ['class' => 'b-btn b-primary']) ?>
        <?php endif; ?>

        <?php if (permission('questions', 'upload')): ?>
            <?= Html::a(Yii::t('app', 'Savol yuklash excel'), ['upload',  'id' => $subject->id],
                [
                    "data-bs-toggle" => "modal",
                    "data-bs-target" => "#studentInfo",
                    'class' => 'b-btn b-primary'
                ])
            ?>
        <?php endif; ?>

        <?php if (permission('subjects', 'delete-questions')): ?>
            <?= Html::a(Yii::t('app', 'Barcha savollarni o\'chirish'), ['subjects/delete-questions',  'id' => $subject->id],
                [
                    'class' => 'b-btn b-danger'
                ])
            ?>
        <?php endif; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Savol matni',
                'contentOptions' => ['date-label' => 'Savol matni' ,'style' => 'max-width: 300px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->text;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'entered' => function ($url, $model) {
                        if ($model->status == 1) {
                            return false;
                        } else {
                            if (permission('questions', 'check')) {
                                $url = Url::to(['check', 'id' => $model->id]);
                                return Html::a('<i class="fa-solid fa-check"></i>', $url, [
                                    'title'        => 'Tasdiqlash',
                                    'class' => 'tableIcon',
                                    'data-confirm' => Yii::t('yii', 'Savol tasdiqlansinmi?'),
                                    'data-method'  => 'post',
                                ]);
                            }
                        }
                    },
                    'view'   => function ($url, $model) {
                        if (permission('questions', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        if (permission('questions', 'update')) {
                            $url = Url::to(['update', 'id' => $model->id]);
                            return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                                'title' => 'update',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('questions', 'delete')) {
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


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

</div>

<div class="modal fade" id="studentInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-body" id="studentInfoBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    $('#studentInfo').on('show.bs.modal', function (e) {
        $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBody').load(url);
    });
});
JS;
$this->registerJs($js);
?>
