<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;
use kartik\select2\Select2;

/** @var $model */
/** @var $query */
/** @var Student $student */
/** @var $id */
$model->price = $query->contract_price;
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Shartnoma narxi</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'price')->textInput()->label('Narx <span>*</span>') ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>





