<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Subjects $model */

$this->title = $model->name_uz;
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Fanlar ro\'yhati'),
    'url' => ['index'],
];
\yii\web\YiiAsset::register($this);
?>
<div class="course-view">

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

    <p class="mb-3">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'b-btn b-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="grid-view">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name_uz',
                'name_en',
                'name_ru',
                [
                    'attribute' => 'language_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->language->name_uz;
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 1) {
                            return "<span class='editable editable-click'>Faol</span>";
                        } else {
                            return "<span class='editable editable-click editable-empty'>No faol</span>";
                        }
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s' , $model->created_at);
                    }
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s' , $model->updated_at);
                    }
                ],
                [
                    'attribute' => 'created_by',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->created_by == null || $model->created_by == 0) {
                            return 0;
                        }
                        $profile = $model->createdBy->employee;
                        return $profile->first_name . " " .$profile->last_name. " ". $profile->middle_name;
                    }
                ],
                [
                    'attribute' => 'updated_by',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->updated_by == null || $model->updated_by == 0) {
                            return 0;
                        }
                        $profile = $model->updatedBy->employee;
                        return $profile->first_name . " " .$profile->last_name. " ". $profile->middle_name;
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
