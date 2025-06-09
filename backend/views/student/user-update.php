<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\EduType;

/** @var $model */
/** @var Student $student */
/** @var $id */

$user = $model->user;
$model->status = $user->status;
$data = ($user->status != 0) ? [
    0 =>  'Arxivlangan',
    5 =>  'Blocklangan',
    9 =>  'Raqam tadiqlamagan',
    10 => 'Faol'
] : [];
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Ma'lumotlarni tahrirlash</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'username')
            ->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '+\9\9\8 (99) 999-99-99',
                'options' => [
                    'placeholder' => '+998 (__) ___-__-__',
                ],
            ])->label('Telefon nomer <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'password')->textInput()->label('Parol <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'status')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => Yii::t("app" , "Status tanlang.")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Status tanlang.").' <span>*</span>'); ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>