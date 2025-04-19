<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Actions $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Tahrirlash</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
