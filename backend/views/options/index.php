<?php

use common\models\Options;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\OptionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Options');
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<div class="options-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Options'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'question_id',
            'subject_id',
            'text:ntext',
            'image',
            //'is_correct',
            //'status',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'is_deleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Options $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
