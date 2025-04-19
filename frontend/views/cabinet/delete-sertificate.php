<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
/** @var $model */
?>


<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'method' => 'post'
]); ?>

<div class="alert_question">
    <h1><i class="fa-solid fa-question"></i></h1>
    <p>
        <?= Yii::t("app" , "a109") ?>
    </p>
</div>

<div class="form-group d-none">
    <?= $form->field($model, 'status')->textInput() ?>
</div>

<div class="d-flex justify-content-around align-items-center top30">
    <?= Html::button(Yii::t("app" , "a110"), ['class' => 'step_left_btn step_btn', 'data-bs-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t("app" , "a111"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); ?>
