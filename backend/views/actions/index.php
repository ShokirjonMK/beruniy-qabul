<?php

use common\models\Actions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ActionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Sahifalar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="actions-index">

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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'controller',
            'action',
            'description',
            [
                'attribute' => 'status',
                'contentOptions' => ['date-label' => 'status'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<div class='badge-table-div active'>" . ($model->status == 0 ? 'action' : 'Controller') . "</div>";
                },
                'filter' => Html::dropDownList(
                    'status',
                    $searchModel->status,
                    [0 => 'action', 1 => 'Controller'],
                    [
                        'class' => 'form-control selectpicker', // Bootstrap Select sinfi
                        'prompt' => 'Tanlang', // Bo‘sh filter
                        'data-live-search' => "true" // Qidirish funksiyasi
                    ]
                ),
            ],
            [
                'attribute' => 'Tahrirlash',
                'contentOptions' => ['date-label' => 'Tahrirlash'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Yii::t('app', 'Tahrirlash'), ['update',  'id' => $model->id],
                        [
                            "data-bs-toggle" => "modal",
                            "data-bs-target" => "#actionsLoad",
                            "class" => "badge-table-div active",
                        ]);
                },
            ],
            [
                'attribute' => 'O\'chirish',
                'contentOptions' => ['date-label' => 'O\'chirish'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Yii::t('app', 'O\'chirish'), ['delete',  'id' => $model->id],
                        [
                            "data-method" => "post",
                            "data-confirm" => "Siz rostdan ma\'lumotni o\'chirmoqchimisiz?",
                            "class" => "badge-table-div danger",
                        ]);
                },
            ],
        ],
    ]); ?>
</div>

<div class="modal fade" id="actionsLoad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    $('#actionsLoad').on('show.bs.modal', function (e) {
        $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBody').load(url);
    });
});
JS;
$this->registerJs($js);
?>

