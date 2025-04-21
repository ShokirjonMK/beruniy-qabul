<?php

use common\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\MenuSearch $searchModel */
/** @var common\models\Menu $menu */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', $menu->name);
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Menyular'),
    'url' => ['menu/index'],
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

    <div class="mb-3 mt-4">
        <?= Html::a(Yii::t('app', 'Qo\'shish'), ['sub-menu-create' , 'id' => $menu->id], ['class' => 'b-btn b-primary']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'URL',
                'contentOptions' => ['date-label' => 'URL'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<div class='badge-table-div active'>".$model->action->controller."/".$model->action->action."</div>";
                },
            ],
            [
                'attribute' => 'Tahrirlash',
                'contentOptions' => ['date-label' => 'Tahrirlash'],
                'format' => 'raw',
                'value' => function($model) {
                    if (permission('menu', 'sub-menu-update')) {
                        return Html::a(Yii::t('app', 'Tahrirlash'), ['sub-menu-update',  'id' => $model->id],
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
