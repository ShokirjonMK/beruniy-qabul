<?php

use common\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\MenuSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Menyular');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="page">

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

    <?php if (permission('menu', 'create')): ?>
        <p class="mb-3 mt-4">
            <?= Html::a('Qo\'shish', ['create'], ['class' => 'b-btn b-primary']) ?>
        </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
//            'action_id',
//            'icon',
            [
                'attribute' => 'Url',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->action_id != null) {
                        return "<p>". $model->action->controller."/".$model->action->action ."</p>";
                    } else {
                        return "";
                    }
                }
            ],
            [
                'attribute' => 'Pastki menyu',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->action_id == null) {
                        if (permission('menu', 'sub-menu')) {
                            $url = Url::to(['menu/sub-menu', 'id' => $model->id]);
                            return Html::a('Ichki menyu', $url, [
                                'title' => 'Pastki menyular ro\'yhati',
                                'class' => "badge-table-div active",
                            ]);
                        }
                    } else {
                        return "<div class='badge-table-div active'>url</div>";
                    }
                }
            ],
            [
                'attribute' => 'Tahrirlash',
                'contentOptions' => ['date-label' => 'Tahrirlash'],
                'format' => 'raw',
                'value' => function($model) {
                    if (permission('menu', 'update')) {
                        return Html::a(Yii::t('app', 'Tahrirlash'), ['update',  'id' => $model->id],
                            [
                                "class" => "badge-table-div active",
                            ]);
                    }
                },
            ],
            [
                'attribute' => 'O\'chirish',
                'contentOptions' => ['date-label' => 'O\'chirish'],
                'format' => 'raw',
                'value' => function($model) {
                    if (permission('menu', 'delete')) {
                        return Html::a(Yii::t('app', 'O\'chirish'), ['delete',  'id' => $model->id],
                            [
                                "data-method" => "post",
                                "data-confirm" => "Siz rostdan ma\'lumotni o\'chirmoqchimisiz?",
                                "class" => "badge-table-div danger",
                            ]);
                    }
                },
            ],
        ],
    ]); ?>

</div>
