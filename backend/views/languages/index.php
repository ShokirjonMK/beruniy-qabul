<?php

use common\models\Languages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\LanguagesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tillar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="languages-index">

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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['date-label' => '№'],
            ],
            [
                'attribute' => 'name_uz',
                'contentOptions' => ['date-label' => 'name_uz'],
            ],
            [
                'attribute' => 'name_en',
                'contentOptions' => ['date-label' => 'name_en'],
            ],
            [
                'attribute' => 'name_ru',
                'contentOptions' => ['date-label' => 'name_ru'],
            ],
        ],
    ]); ?>


</div>
