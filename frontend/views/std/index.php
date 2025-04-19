<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var \frontend\models\ContractSearch $model */
/** @var ActiveForm $form */
?>
<div class="step_one_box">
    <div>
        <div class="login_p top40">
            <h4><?= Yii::t("app" , "a157") ?></h4>
            <p><?= Yii::t("app" , "a158") ?></p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'method' => 'get',
            'options' => ['class' => 'top40'],
            'fieldConfig' => [
                'template' => '{label}{input}{error}',
            ]
        ]); ?>

        <div class="form-group">
            <?= $form->field($model, 'pin')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '99999999999999',
                'options' => [
                    'placeholder' => '______________',
                ],
            ])->label(Yii::t("app" , "a156").' <span>*</span>') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'seria')->textInput([
                'maxlength' => true,
                'placeholder' => '__',
                'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
            ])->label(Yii::t("app" , "a50").' <span>*</span>') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'number')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '9999999',
                'options' => [
                    'placeholder' => '_______',
                ],
            ])->label(Yii::t("app" , "a51").' <span>*</span>') ?>
        </div>

        <div class="step_btn_block top40">
            <?= Html::submitButton(Yii::t("app" , "a155"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>