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

$this->title = 'Shartnoma qilinganlar';
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

    <?= $this->render('_searchContract', ['model' => $searchModel]); ?>

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->fullName.' | '.$model->passport_serial.' '.$model->passport_number.' | '.$model->passport_pin;
            },
        ],
        [
            'attribute' => 'Yo\'nalishi',
            'contentOptions' => ['date-label' => 'Yo\'nalishi' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->direction->name ?? '----';
            },
        ],
        [
            'attribute' => 'Ta\'lim turi',
            'contentOptions' => ['date-label' => 'Ta\'lim turi'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->eduType->name_uz ?? '----';
            },
        ],
        [
            'attribute' => 'Ta\'lim shakli',
            'contentOptions' => ['date-label' => 'Ta\'lim shakli'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->eduForm->name_uz ?? '----';
            },
        ],
        [
            'attribute' => 'Ta\'lim tili',
            'contentOptions' => ['date-label' => 'Ta\'lim tili'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->lang->name_uz ?? '----';
            },
        ],
        [
            'attribute' => 'Bosqich',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'Ta\'lim shakli'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->edu_type_id == 2 && $model->course_id != null) {
                    $courseId = $model->course_id + 1;
                    return Course::findOne($courseId)->name_uz;
                }
                return "1 - bosqich";
            },
        ],
        [
            'attribute' => 'Telefon raqami',
            'contentOptions' => ['date-label' => 'Telefon raqami'],
            'format' => 'raw',
            'value' => function($model) {
                $user = $model->user;
                $text = "<br><div class='badge-table-div active mt-2'>".date("Y-m-d H:i:s" , $user->created_at)."</div>";
                return $user->username.$text;
            },
        ],
        [
            'attribute' => 'Status',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->contractStatus;
            },
        ],
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                $readMore = "<a href='".Url::to(['student/view' , 'id' => $model->id])."' class='badge-table-div active mt-2'>Batafsil</a>";
                return $readMore;
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
