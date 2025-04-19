<?php

use common\models\Questions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Subjects $subject */
/** @var common\models\QuestionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Bot savollari');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => 'Fanlar ( '.$subject->name_uz . ' )',
    'url' => ['subjects/index' , 'id' => $subject->id],
];

$questions = $dataProvider->getModels();
?>
<div class="questions-index">
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
        <?= Html::a(Yii::t('app', 'Savol qo\'shish'), ['bot-create' , 'id' => $subject->id], ['class' => 'b-btn b-primary']) ?>
    </div>
    <?php if (count($questions) > 0): ?>
        <?php foreach ($questions as $question): ?>
            <div class="questions_page">
                <div class="form-section">
                    <div class="form-section_item">
                        <div class="qs_head">
                            <div class="qs_head_left">
                                <p>Savol ID: &nbsp; <strong><?= $question->id ?></strong></p>
                            </div>
                            <div class="qs_head_right">
                                <?php if ($question->status != 1) : ?>
                                    <?= Html::a(Yii::t('app', '<i class="far fa-check-circle"></i>'), ['bot-check', 'id' => $question->id], [
                                        'class' => '',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Savol tasdiqlansinmi?'),
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                                <a href="<?= Url::to(['bot-update' , 'id' => $question->id]) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['delete', 'id' => $question->id], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                        <div class="qs_body">
                            <?php if ($question->text != null) : ?>
                                <div class="qs_content">
                                    <p><?= $question->text ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($question->image != null) : ?>
                                <div class="qs_content">
                                    <div class="qs_image">
                                        <img src="/backend/web/uploads/questions/<?= $question->image ?>">
                                        <!--                                --><?php //if ($model->status != 1) : ?>
                                        <?= Html::a(Yii::t('app', '<i class="fa-solid fa-trash"></i>'), ['img-delete', 'id' => $question->id], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                        <!--                                --><?php //endif; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php $options = $question->options; ?>
                        <?php if (count($options) > 0) : ?>
                            <?php foreach ($options as $option) : ?>
                                <div class="qs_option">
                                    <div class="qs_option_head">
                                        <div class="qs_option_left <?php if ($option->is_correct == 1) { echo "active";} ?>">
                                            <p>Variant ID: &nbsp; <strong><?= $option->id ?></strong></p>
                                        </div>
                                        <div class="qs_option_right"></div>
                                    </div>
                                    <div class="qs_option_body">
                                        <?php if ($option->text != null) : ?>
                                            <p><?= $option->text ?></p>
                                        <?php endif; ?>
                                        <?php if ($option->image != null) : ?>
                                            <div class="qs_option_image <?php if ($option->text != null) { echo "mtop10";} ?>">
                                                <img src="/backend/web/uploads/options/<?= $option->image ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>



</div>
