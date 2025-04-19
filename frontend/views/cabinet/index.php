<?php

use common\models\{Student, Status, Course, Exam};
use yii\helpers\Url;

/** @var $student */

$lang = Yii::$app->language;
$this->title = Yii::t("app", "a40");
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a40") ?></h6>
                <span></span>
            </div>

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
