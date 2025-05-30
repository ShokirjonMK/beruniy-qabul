<?php

use common\models\{Student, Status, Course, Exam};
use yii\helpers\Url;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\StudentMaster;
use yii\helpers\Html;

/** @var $student */

$lang = Yii::$app->language;
$this->title = Yii::t("app", "a40");
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$t = false;
$online = true;
if ($student->edu_type_id == 1) {
    $exam = Exam::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'is_deleted' => 0
    ]);
    if ($exam->status == 3) {
        $t = true;
        if ($student->exam_type == 1) {
            $online = false;
        }
    }
} elseif ($student->edu_type_id == 2) {
    $exam = StudentPerevot::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($exam->file_status == 2) {
        $t = true;
    }
    $courseId = $student->course_id + 1;
    $course = Course::findOne(['id' => $courseId]);
} elseif ($student->edu_type_id == 3) {
    $exam = StudentDtm::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($exam->file_status == 2) {
        $t = true;
    }
} elseif ($student->edu_type_id == 4) {
    $master = StudentMaster::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($master->file_status == 2) {
        $t = true;
    }
}
$session = Yii::$app->session;
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a40") ?></h6>
                <span></span>
            </div>

            <?php if ($t && $online) : ?>
                <?php if ($student->edu_type_id == 1) : ?>
                    <?= $this->render('_contract'); ?>
                <?php else: ?>
                    <?= $this->render('_no-contract'); ?>
                <?php endif; ?>
            <?php endif; ?>

            <div class="ika_user_page">
                <div class="row">
                    <?php
                    $userDetails = [
                        'ID' => $student->user_id,
                        'F.I.SH' => $student->fullName,
                        'Pasport ma\'lumoti' => $student->passport_serial . " " . $student->passport_number,
                        'Telefon raqami' => $student->username,
                        'Parolingiz' => $student->password,
                        'Status' => 'Faol'
                    ];

                    // direction null emasligini tekshiramiz
                    $directionName = $direction ? ($direction->code . " - " . ($direction['name_' . $lang] ?? '---')) : 'Yo‘nalish ma’lumotlari mavjud emas';

                    $eduDetails = [
                        'Qabul turi' => $eduDirection->eduType['name_' . $lang] ?? '---',
                        'Filial' => $student->branch['name_' . $lang] ?? '---',
                        'Yo‘nalish' => $directionName,
                        'Ta\'lim shakli' => $eduDirection->eduForm['name_' . $lang] ?? '---',
                        'Ta\'lim tili' => $eduDirection->lang['name_' . $lang] ?? '---'
                    ];

                    if ($student->edu_type_id == 1) {
                        $eduDetails[Yii::t("app", "a64")] = Status::getExamStatus($student->exam_type);
                        if ($student->exam_type == 1 && $student->examDate) {
                            $eduDetails['Imtixon sanasi'] = $student->examDate->date ?? '---';
                        }
                    }

                    if ($student->edu_type_id == 2) {
                        $courseName = Course::findOne(['id' => ($student->course_id + 1)]);
                        $eduDetails[Yii::t("app", "a81")] = $courseName['name_' . $lang] ?? '----';
                        $eduDetails['Avvalgi OTM nomi'] = $student->edu_name ?? '----';
                        $eduDetails['Avvalgi yo\'nalish nomi'] = $student->edu_direction ?? '----';
                    }

                    function renderList($data)
                    {
                        foreach ($data as $key => $value) {
                            echo "<ul><li>{$key}:</li><li><p>{$value}</p></li></ul>";
                        }
                    }
                    ?>

                    <div class="col-lg-6 col-md-12">
                        <div class="ika_user_page_item">
                            <?php renderList($userDetails); ?>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="ika_user_page_item">
                            <?php renderList($eduDetails); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($session->has('exam_modal_show')) : ?>
    <div class="modal fade" id="showExamModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="perOferta">
                    <div class="alert_question">
                        <div class="alert_danger_circle">
                            <div class="alert_danger_box">
                                <i class="fa-solid fa-question"></i>
                            </div>
                        </div>
                        <p style="text-transform: unset">
                            <?= Yii::t("app" , "a166") ?>
                        </p>
                    </div>
                    <div class="d-flex justify-content-around align-items-center top30">
                        <?= Html::button(Yii::t("app" , "a110"), ['class' => 'step_left_btn step_btn', 'data-bs-dismiss' => 'modal']) ?>
                        <?= Html::a(Yii::t("app", "a111"), ['cabinet/test'], ['class' => 'step_right_btn step_btn']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
var examModal = new bootstrap.Modal(document.getElementById('showExamModal'));
examModal.show();
JS;
$this->registerJs($js);
Yii::$app->session->remove('exam_modal_show');
?>
<?php endif; ?>




