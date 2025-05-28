<?php

use common\models\Telegram;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\TelegramSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Telegram bot');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];

?>
<div class="telegram-index">

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

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'telegram_id',
            'phone',
            'username',
            'step',
            'cons_id',
            'branch_id',
            'lang_id',
            'type',
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
                'header'=> 'Harakatlar',
                'buttons'  => [
                    'view'   => function ($url, $model) {
                        if (permission('telegram', 'view')) {
                            $url = Url::to(['view', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
                                'title' => 'view',
                                'class' => 'tableIcon',
                            ]);
                        }
                        return false;
                    },
                    'update' => function ($url, $model) {
                        return false;
                    },
                    'delete' => function ($url, $model) {
                        if (permission('telegram', 'delete')) {
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


</div>
