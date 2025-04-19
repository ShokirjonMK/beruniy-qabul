<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Lang $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lang-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-6'>    <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>

</div><div class='col-6'>    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

</div><div class='col-6'>    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

</div><div class='col-6'>    <?= $form->field($model, 'status')->textInput() ?>

</div><div class='col-6'>    <?= $form->field($model, 'created_at')->textInput() ?>

</div><div class='col-6'>    <?= $form->field($model, 'updated_at')->textInput() ?>

</div><div class='col-6'>    <?= $form->field($model, 'created_by')->textInput() ?>

</div><div class='col-6'>    <?= $form->field($model, 'updated_by')->textInput() ?>

</div><div class='col-6'>    <?= $form->field($model, 'is_deleted')->textInput() ?>

</div>    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
