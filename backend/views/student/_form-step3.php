<?php

use common\models\Direction;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Student;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Lang;
use common\models\EduType;
use common\models\EduForm;
use common\models\Status;
use common\models\EduDirection;
use common\models\Branch;
use common\models\ExamDate;

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
$model->exam_type = $student->exam_type;
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
if ($student->exam_type != 0) {
    $examDates = ExamDate::find()
        ->where([
            'is_deleted' => 0,
            'status' => 1,
            'branch_id' => $student->branch_id
        ])
        ->orderBy(['date' => SORT_ASC])->all();
}
$exam = [];
if ($model->edu_direction_id != null) {
    $eduDirection = $student->eduDirection;
    if ($eduDirection->exam_type != null) {
        $examTypes = json_decode($eduDirection->exam_type, true);
        foreach ($examTypes as $examType) {
            $exam[$examType] = Status::getExamStatus($examType);
        }
    }
}

?>
<div class="modal-header mt-2">
    <h1 class="modal-title" id="exampleModalLabel">Yo'nalish tanlang</h1>
    <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
</div>

<div class="step_one_box mt-3">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'top40'],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'filial_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
            'options' => ['placeholder' => Yii::t("app" , "Filial tanlang")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Filial tanlang").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'lang_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($languages, 'id', 'name_uz'),
            'options' => ['placeholder' => Yii::t("app" , "Ta'lim tili")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Ta'lim tili").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_form_id')->widget(Select2::classname(), [
            'data' =>  ArrayHelper::map($eduForms, 'id', 'name_uz'),
            'options' => ['placeholder' => Yii::t("app" , "Ta'lim shakli")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Ta'lim shakli").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_direction_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => Yii::t("app" , "Ta'lim yo'nalishi")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Ta'lim yo'nalishi").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'exam_type')->widget(Select2::classname(), [
            'data' => $exam,
            'options' => ['placeholder' => Yii::t("app" , "Imtixon turi")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "Imtixon turi").' <span>*</span>');; ?>
    </div>

    <div class="examDateIk">
        <?php if ($student->exam_type != 0) : ?>
            <div class="row">
                <?php foreach ($examDates as $examDate) : ?>
                    <?php
                    $checked = '';
                    if ($student->exam_date_id == $examDate->id) {
                        $checked = 'checked';
                    }
                    ?>
                    <div class='col-md-6 col-sm-12 col-12'>
                        <div class='exam-date-item bot20'>
                            <label for='check_<?= $examDate->id ?>' class='permission_label2'>
                                <div class='d-flex gap-2 align-items-center'>
                                    <input type='radio' class='bu-check' name='StepThreeOne[exam_date_id]' id='check_<?= $examDate->id ?>' value='<?= $examDate->id ?>' <?= $checked ?>>
                                    <span>Imtihon sanasi:</span>
                                </div>
                                <p><?= date('Y-m-d H:i', strtotime($examDate->date)) ?></p>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#stepthreeone-lang_id").on('change', function () {
            var form_id = $("#stepthreeone-edu_form_id").val();
            var branch_id = $("#stepthreeone-filial_id").val();
            $("#stepthreeone-exam_type").html(false);
            $(".examDateIk").html(false);
            var lang_id = $(this).val();
            var std_id = $student->id;
            if (form_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, std_id: std_id, branch_id:branch_id},
                    type: 'GET',
                    success: function (data) {
                        $("#stepthreeone-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        
        $("#stepthreeone-edu_form_id").on('change', function () {
            var lang_id = $("#stepthreeone-lang_id").val();
            var branch_id = $("#stepthreeone-filial_id").val();
            $("#stepthreeone-exam_type").html(false);
            $(".examDateIk").html(false);
            var form_id = $(this).val();
            var std_id = $student->id;
            if (lang_id > 0 && branch_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, std_id: std_id, branch_id:branch_id},
                    type: 'GET',
                    success: function (data) {
                        $("#stepthreeone-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            }
        });
        
        $("#stepthreeone-exam_type").on('change', function () {
            var branch_id = $("#stepthreeone-filial_id").val();
            var exam_type_id = $(this).val();
            var std_id = $student->id;
            if (exam_type_id == 1 && branch_id > 0) {
                $.ajax({
                    url: '../file/exam-date/',
                    data: {branch_id: branch_id, std_id:std_id},
                    type: 'GET',
                    success: function (data) {
                        $(".examDateIk").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            } else {
                $(".examDateIk").html('');
            }
        });
        
        $("#stepthreeone-filial_id").on('change', function () {
            var branch_id = $(this).val();
            var lang_id = $("#stepthreeone-lang_id").val();
            var form_id = $("#stepthreeone-edu_form_id").val();
            var exam_type_id = $("#stepthreeone-exam_type").val();
            $("#stepthreeone-exam_type").html('');
            var std_id = $student->id;
            if (exam_type_id == 1 && branch_id > 0) {
                $.ajax({
                    url: '../file/exam-date/',
                    data: {branch_id: branch_id, std_id:std_id},
                    type: 'GET',
                    success: function (data) {
                        $(".examDateIk").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            } else {
                $(".examDateIk").html('');
            }
            
            if (lang_id > 0 && form_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id, branch_id:branch_id, std_id:std_id},
                    type: 'GET',
                    success: function (data) {
                        $("#stepthreeone-edu_direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik21212!!!");
                    }
                });
            }
            
        });
        
        $("#stepthreeone-edu_direction_id").on('change', function () {
            var dir_id = $(this).val();
            $(".examDateIk").html(false);
            if (dir_id > 0) {
                $.ajax({
                    url: '../file/exam-type/',
                    data: {dir_id: dir_id},
                    type: 'GET',
                    success: function (data) {
                        $("#stepthreeone-exam_type").html(data);
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




