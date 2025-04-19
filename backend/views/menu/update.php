<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Actions;

/** @var yii\web\View $this */
/** @var common\models\Menu $model */

$this->title = Yii::t('app', 'O\'zgartirish');
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

    <!--   Form   -->
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-section">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'action_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(Actions::find()->where(['status' => 0])->all(), 'id', 'description'),
                            'options' => ['placeholder' => 'Select a state ...'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group d-flex justify-content-end mt-4 mb-5">
        <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'b-btn b-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <!--   Form   -->

</div>
