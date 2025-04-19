<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model */
?>


<?php $form = ActiveForm::begin([
    'id' => 'login-form',
]); ?>

<div class="file_box">
    <?= $this->render('/layouts/_upload');?>
    <div class="file1212">
        <?= $form->field($model, 'file')->fileInput(['id'=>'upload','hidden'=>'hidden','class'=>'click_file'])->label(false) ?>
    </div>
</div>


<div class="d-flex justify-content-around align-items-center top30">
    <?= Html::button(Yii::t("app" , "a73"), ['class' => 'step_left_btn step_btn', 'data-bs-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t("app" , "a145"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); ?>
