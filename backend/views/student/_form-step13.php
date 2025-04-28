<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;

/** @var $model */
/** @var Student $student */
/** @var $id */

$model->last_name = $student->last_name;
$model->first_name = $student->first_name;
$model->middle_name = $student->middle_name;
$model->passport_serial = $student->passport_serial;
$model->passport_number = $student->passport_number;
$model->birthday = $student->birthday;
$model->passport_pin = $student->passport_pin;
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Pasport ma'lumotini tahrirlash</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'last_name')->textInput(['placeholder' => Yii::t("app" , "Familya")])->label(Yii::t("app" , "Familya").' <span>*</span>') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'first_name')->textInput(['placeholder' => Yii::t("app" , "Ism")])->label(Yii::t("app" , "Ism").' <span>*</span>') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'middle_name')->textInput(['placeholder' => Yii::t("app" , "Otasi")])->label(Yii::t("app" , "Otasi")) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'passport_serial')->textInput([
            'maxlength' => true,
            'placeholder' => '__',
            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
        ])->label(Yii::t("app" , "Pasport seriya").' <span>*</span>') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'passport_number')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '9999999',
            'options' => [
                'placeholder' => '_______',
            ],
        ])->label(Yii::t("app" , "Pasport raqam").' <span>*</span>') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t("app" , "Tug'ilgan sana")],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy'
            ]
        ])->label(Yii::t("app" , "Tug\'ilgan sana").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'passport_pin')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '99999999999999',
            'options' => [
                'placeholder' => '______________',
            ],
        ])->label(Yii::t("app" , "JSHSHIR").' <span>*</span>') ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>





