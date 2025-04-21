<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Questions $model */

$subject = $model->subject;
$options = $model->options;
$this->title = $model->id;
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => $subject->name_uz,
    'url' => ['subjects/view' , 'id' => $subject->id],
];
$breadcrumbs['item'][] = [
    'label' => 'Savollar',
    'url' => ['index' , 'id' => $subject->id],
];
\yii\web\YiiAsset::register($this);
?>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<div class="questions-view">

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

    <?php if (permission('options', 'create')): ?>
        <div class="mb-3 mt-4">
            <?= Html::a(Yii::t('app', 'Variant qo\'shish'), ['options/create' , 'id' => $model->id], ['class' => 'b-btn b-primary']) ?>
        </div>
    <?php endif; ?>

    <div class="questions_page">
        <div class="form-section">
            <div class="form-section_item">
                <div class="qs_head">
                    <div class="qs_head_left">
                        <p>Savol ID: &nbsp; <strong><?= $model->id ?></strong></p>
                    </div>
                    <div class="qs_head_right">
                        <?php if ($model->status != 1) : ?>
                            <?php if (permission('questions', 'check')): ?>
                                <?= Html::a(Yii::t('app', '<i class="far fa-check-circle"></i>'), ['check', 'id' => $model->id], [
                                'class' => '',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Savol tasdiqlansinmi?'),
                                    'method' => 'post',
                                    ],
                                ]) ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (permission('questions', 'update')): ?>
                            <a href="<?= Url::to(['update' , 'id' => $model->id]) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <?php endif; ?>

                        <?php if (permission('questions', 'delete')): ?>
                            <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['delete', 'id' => $model->id], [
                                'class' => '',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="qs_body">
                    <?php if ($model->text != null) : ?>
                        <div class="qs_content">
                            <p><?= $model->text ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->image != null) : ?>
                        <div class="qs_content">
                            <div class="qs_image">
                                <img src="/backend/web/uploads/questions/<?= $model->image ?>">
                                <?php if (permission('questions', 'img-delete')): ?>
                                    <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['img-delete', 'id' => $model->id], [
                                        'class' => '',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (count($options) > 0) : ?>
                    <?php foreach ($options as $option) : ?>
                        <div class="qs_option">
                            <div class="qs_option_head">
                                <div class="qs_option_left <?php if ($option->is_correct == 1) { echo "active";} ?>">
                                    <p>Variant ID: &nbsp; <strong><?= $option->id ?></strong></p>
                                </div>
                                <div class="qs_option_right">
                                    <?php if (permission('options', 'update')): ?>
                                        <a href="<?= Url::to(['options/update' , 'id' => $option->id]) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <?php endif; ?>

                                    <?php if (permission('options', 'delete')): ?>
                                        <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['options/delete', 'id' => $option->id], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <div class="qs_option_body">
                                <?php if ($option->text != null) : ?>
                                    <p><?= $option->text ?></p>
                                <?php endif; ?>
                                <?php if ($option->image != null) : ?>
                                    <div class="qs_option_image <?php if ($option->text != null) { echo "mtop10";} ?>">
                                        <img src="/backend/web/uploads/options/<?= $option->image ?>">
                                        <?php if (permission('options', 'img-delete')): ?>
                                            <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['options/img-delete', 'id' => $option->id], [
                                                'class' => '',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>
