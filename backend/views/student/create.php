<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\EduType;
use common\models\Branch;

/** @var $model */
/** @var Student $student */
/** @var $id */
$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Yangi talaba qo'shish</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'username')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '+\9\9\8 (99) 999-99-99',
            'options' => [
                'placeholder' => '+998 (__) ___-__-__',
            ],
        ]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'filial_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
            'options' => ['placeholder' => 'Filial tanlang'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Filial tanlang'); ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>