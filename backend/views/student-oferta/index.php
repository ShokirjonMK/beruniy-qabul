<?php

use common\models\StudentOferta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var common\models\StudentOfertaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Kelib tushgan ofertalar';
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

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.SH',
            'contentOptions' => ['date-label' => 'F.I.SH' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                $student = $model->student;
                return $student->FullName;
            },
        ],
        [
            'attribute' => 'Yo\'nalish',
            'contentOptions' => ['date-label' => 'Yo\'nalish' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                $direction = $model->direction;
                return $direction->name;
            },
        ],
        [
            'attribute' => 'Telefon raqam',
            'contentOptions' => ['date-label' => 'Telefon raqam'],
            'format' => 'raw',
            'value' => function($model) {
                $student = $model->student;
                return $student->username;
            },
        ],
        [
            'attribute' => 'Status',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                return "<div class='badge-table-div active mt-2'>".Status::fileStatus($model->file_status)."</div>";
            },
        ],
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Batafsil'],
            'format' => 'raw',
            'value' => function($model) {
                $readMore = "<a href='".Url::to(['student/view' , 'id' => $model->student_id])."' class='badge-table-div active mt-2'>Batafsil</a>";
                return $readMore;
            },
        ],
    ] ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount ?></b></p>
                <div class="page_export d-flex align-items-center gap-4">
                    <div>
                        <?php echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $data,
                            'asDropdown' => false,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $data,
    ]); ?>
</div>
