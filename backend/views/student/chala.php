<?php

use common\models\Student;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\EduType;
use common\models\Course;
use common\models\Status;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var common\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var EduType $eduType */

$this->title = 'Chala arizalar';
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

    <?= $this->render('_searchChala', ['model' => $searchModel]); ?>

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->fullName ?? '----';
            },
        ],
        [
            'attribute' => 'Pasport ma\'lumoti',
            'contentOptions' => ['date-label' => 'Pasport ma\'lumoti'],
            'format' => 'raw',
            'value' => function($model) {
                $seria= $model->passport_serial ?? '-- ';
                $number = $model->passport_number ?? ' -------';
                return $seria.' '.$number;
            },
        ],
        [
            'attribute' => 'Telefon raqami',
            'contentOptions' => ['date-label' => 'Telefon raqami'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->username;
            },
        ],
        [
            'attribute' => 'Ro\'yhatga olingan',
            'contentOptions' => ['date-label' => 'Ro\'yhatga olingan'],
            'format' => 'raw',
            'value' => function($model) {
                return date("Y-m-d H:i:s" , $model->user->created_at);
            },
        ],
        [
            'attribute' => 'Status',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                $sms = '';
                $user = $model->user;
                if ($user->sms_time > time()) {
                    $sms = "<br><div class='badge-table-div active mt-2'>".$user->sms_number."</div>";
                }
                return $model->chalaStatus.$sms;
            },
        ],
        [
            'attribute' => 'CONSULTING',
            'contentOptions' => ['date-label' => 'CONSULTING'],
            'format' => 'raw',
            'value' => function($model) {
                $cons = $model->user->cons;
                $branch = $model->branch->name_uz ?? '- - - -';
                return "<a href='https://{$cons->domen}' class='badge-table-div active'>".$cons->domen."</a><br><div class='badge-table-div active mt-2'>".$branch."</div>";
            },
        ],
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                if (permission('student', 'view')) {
                    $readMore = "<a href='".Url::to(['student/view' , 'id' => $model->id])."' class='badge-table-div active mt-2'>Batafsil</a>";
                    return $readMore;
                }
            },
        ],
    ]; ?>

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
        'pager' => [
            'class' => LinkPager::class,
            'pagination' => $dataProvider->getPagination(),
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'nextPageLabel' => false,
            'prevPageLabel' => false,
            'maxButtonCount' => 10,
        ],
    ]); ?>
</div>
