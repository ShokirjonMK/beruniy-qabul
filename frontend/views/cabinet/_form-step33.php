<?php

use common\models\Direction;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Student;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Languages;
use common\models\Lang;
use common\models\EduForm;
use common\models\Status;
use common\models\EduDirection;
use common\models\Branch;

/** @var $model */
/** @var Student $student */
/** @var $id */

$lang = Yii::$app->language;
$languages = Lang::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduForms = EduForm::find()->where(['is_deleted' => 0 , 'status' => 1])->all();

$model->filial_id = $student->branch_id;
$model->lang_id = $student->lang_id;
$model->edu_form_id = $student->edu_form_id;
$model->edu_direction_id = $student->edu_direction_id;
$data = [];
$directions = EduDirection::find()
    ->where([
        'branch_id' => $student->branch_id,
        'edu_type_id' => $student->edu_type_id,
        'edu_form_id' => $model->edu_form_id,
        'lang_id' => $model->lang_id,
        'status' => 1,
        'is_deleted' => 0
    ])->all();
if (count($directions) > 0) {
    foreach ($directions as $direction) {
        $data[$direction->id] = $direction->direction->code.' - '.$direction->direction['name_'.$lang];
    }
}
$branchs = Branch::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="step_one_box">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'top40'],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'filial_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($branchs, 'id', 'name_'.$lang),
            'options' => ['placeholder' => Yii::t("app" , "a160")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a160").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'lang_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($languages, 'id', 'name_'.$lang),
            'options' => ['placeholder' => Yii::t("app" , "a57")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a59").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_form_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($eduForms, 'id', 'name_'.$lang),
            'options' => ['placeholder' => Yii::t("app" , "a58")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a60").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_direction_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => Yii::t("app" , "a61")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a62").' <span>*</span>');; ?>
    </div>

    <div class="step_btn_block top40">
        <?= Html::submitButton(Yii::t("app" , "a52"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#stepthreethree-lang_id").on('change', function () {
            var form_id = $("#stepthreethree-edu_form_id").val();
            var branch_id = $("#stepthreethree-filial_id").val();
            var lang_id = $(this).val();
            $("#stepthreethree-edu_direction_id").html(false);
            if (form_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreethree-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        $("#stepthreethree-edu_form_id").on('change', function () {
            var lang_id = $("#stepthreethree-lang_id").val();
            var branch_id = $("#stepthreethree-filial_id").val();
            var form_id = $(this).val();
            $("#stepthreethree-edu_direction_id").html(false);
            if (lang_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreethree-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            }
        });
        
        $("#stepthreethree-filial_id").on('change', function () {
            var lang_id = $("#stepthreethree-lang_id").val();
            var form_id = $("#stepthreethree-edu_form_id").val();
            var branch_id = $(this).val();
            $("#stepthreethree-edu_direction_id").html(false);
            if (lang_id > 0 && form_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreethree-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            }
        });
    });
JS;
$this->registerJs($js);
?>




