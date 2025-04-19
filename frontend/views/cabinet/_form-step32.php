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
use common\models\DirectionCourse;
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
$model->edu_type_id = $student->edu_type_id;
$model->edu_direction_id = $student->edu_direction_id;
$model->direction_course_id = $student->direction_course_id;
$model->edu_name = $student->edu_name;
$model->edu_direction = $student->edu_direction;
$data = [];
$courses = [];
if ($model->direction_course_id != null && $model->edu_direction_id != null) {
    $directionCourses = DirectionCourse::find()
        ->where([
            'edu_direction_id' => $model->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ])->all();
    if (count($directionCourses) > 0) {
        foreach ($directionCourses as $directionCours) {
            $courses[$directionCours->id] = $directionCours->course['name_'.$lang];
        }
    }
}
if ($model->edu_direction_id != null) {
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

    <div class="form-group">
        <?= $form->field($model, 'direction_course_id')->widget(Select2::classname(), [
            'data' => $courses,
            'options' => ['placeholder' => Yii::t("app" , "a74")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a75").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_name')->textInput(['placeholder' => Yii::t("app" , "a76")])->label(Yii::t("app" , "a77").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_direction')->textInput(['placeholder' => Yii::t("app" , "a78")])->label(Yii::t("app" , "a79").' <span>*</span>');; ?>
    </div>

    <div class="step_btn_block top40">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#stepthreetwo-lang_id").on('change', function () {
            var form_id = $("#stepthreetwo-edu_form_id").val();
            var branch_id = $("#stepthreetwo-filial_id").val();
            var lang_id = $(this).val();
            $("#stepthreetwo-edu_direction_id").html(false);
            if (form_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id , branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreetwo-edu_direction_id").html(data);
                        $("#stepthreetwo-direction_course_id").html(false);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        $("#stepthreetwo-edu_form_id").on('change', function () {
            var lang_id = $("#stepthreetwo-lang_id").val();
            var branch_id = $("#stepthreetwo-filial_id").val();
            var form_id = $(this).val();
            $("#stepthreetwo-edu_direction_id").html(false);
            if (lang_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreetwo-edu_direction_id").html(data);
                        $("#stepthreetwo-direction_course_id").html(false);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        
        $("#stepthreetwo-filial_id").on('change', function () {
            var lang_id = $("#stepthreetwo-lang_id").val();
            var form_id = $("#stepthreetwo-edu_form_id").val();
            var branch_id = $(this).val();
            $("#stepthreetwo-edu_direction_id").html(false);
            if (lang_id > 0 && form_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreetwo-edu_direction_id").html(data);
                        $("#stepthreetwo-direction_course_id").html(false);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        
        $("#stepthreetwo-edu_direction_id").on('change', function () {
            var dir_id = $(this).val();
            if (dir_id > 0) {
                $.ajax({
                    url: '../file/direction-course/',
                    data: {dir_id: dir_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthreetwo-direction_course_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
    });
JS;
$this->registerJs($js);
?>




