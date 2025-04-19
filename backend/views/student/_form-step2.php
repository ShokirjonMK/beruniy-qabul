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
$eduTypes = EduType::find()->where(['status' => 1, 'is_deleted' => 0])->all();
$model->edu_type_id = $student->edu_type_id;
?>

<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Qabul turini tanlang</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'edu_type_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($eduTypes, 'id', 'name_uz'),
            'options' => ['placeholder' => Yii::t("app" , "Ta'lim turini tanlang.")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Ta'lim turini tanlang.").' <span>*</span>'); ?>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>