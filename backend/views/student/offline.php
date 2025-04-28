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
use common\models\Exam;

/** @var yii\web\View $this */
/** @var common\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var EduType $eduType */

$this->title = 'Offline imtixonga arizalar';
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

    <?= $this->render('_offlineSearch', ['model' => $searchModel]); ?>

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->fullName;
            },
        ],
        [
            'attribute' => 'Pasport ma\'lumoti',
            'contentOptions' => ['date-label' => 'Pasport ma\'lumoti' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->passport_serial.' '.$model->passport_number.' | '.$model->passport_pin;
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
            'attribute' => 'Ro\'yhatga olingan sana',
            'contentOptions' => ['date-label' => 'Ro\'yhatga olingan sana'],
            'format' => 'raw',
            'value' => function($model) {
                return "<div class='badge-table-div active'>".date("Y-m-d H:i:s" , $model->user->created_at)."</div>";
            },
        ],
        [
            'attribute' => 'Imtixon sanasi',
            'contentOptions' => ['date-label' => 'Imtixon sanasi'],
            'format' => 'raw',
            'value' => function($model) {
                return "<div class='badge-table-div active mt-2'>".$model->examDate->date ?? '------'."</div>";
            },
        ],
        [
            'attribute' => 'Imtixon xolati',
            'contentOptions' => ['date-label' => 'Imtixon xolati'],
            'format' => 'raw',
            'value' => function($model) {
                $exam = Exam::findOne([
                    'edu_direction_id' => $model->edu_direction_id,
                    'student_id' => $model->id,
                    'is_deleted' => 0,
                ]);

                if ($exam) {
                    return "<div class='badge-table-div active'>".Status::getExamTestStatus($exam->status)."</div>";
                }
            },
        ],
        [
            'attribute' => 'Status',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                $user = $model->user;
                if ($user->status == 10) {
                    return "<div class='badge-table-div active'>Faol</div>";
                } elseif ($user->status == 9 && $user->step > 0){
                    return "<div class='badge-table-div active'>Parol tiklashda SMS parol kiritmagan</div>";
                } elseif ($user->status == 9 && $user->step == 0){
                    return "<div class='badge-table-div active'>SMS parol kiritmagan</div>";
                } elseif ($user->status == 0){
                    return "<div class='badge-table-div active'>Arxivlangan</div>";
                }
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
