<?php

use yii\helpers\Url;
use common\models\Student;
use common\models\StudentPerevot;
use yii\helpers\Html;
use common\models\StudentOferta;
use common\models\Direction;
use common\models\Exam;
use common\models\Course;
use common\models\Status;
use common\models\ExamSubject;

/** @var Student $student */
/** @var Direction $direction */

$this->title = Yii::t("app", "a120");
$lang = Yii::$app->language;
$direction = $student->direction;
$eduDirection = $student->eduDirection;

$exam = Exam::findOne([
    'student_id' => $student->id,
    'edu_direction_id' => $eduDirection->id,
    'is_deleted' => 0
]);
$documents = [];
$subjects = [];
if ($exam) {
    $subjects = ExamSubject::find()
        ->where([
            'edu_direction_id' => $eduDirection->id,
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'status' => 1,
            'is_deleted' => 0
        ])->all();
}

$is_exam = false;
if ($eduDirection->is_oferta == 1) {
    $oferta = StudentOferta::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($oferta->file_status == 2) {
        $is_exam = true;
    }
} else {
    $is_exam = true;
}
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a120") ?></h6>
                <span></span>
            </div>

            <?php if ($exam->status == 3) : ?>
                <?= $this->render('_contract'); ?>
            <?php endif; ?>

            <div class="row top30">
                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Yo'nalish nomi</p>
                        <h6>
                            <?= $direction ? ($direction->code . " - " . ($direction['name_' . $lang] ?? '---')) : 'Yo‘nalish ma’lumotlari mavjud emas' ?>
                        </h6>

                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Ta'lim tili</p>
                        <h6><?= $eduDirection->lang['name_' . $lang] ?? "-" ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Ta'lim shakli</p>
                        <h6><?= $eduDirection->eduForm['name_' . $lang] ?? "-" ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Imtixon turi</p>
                        <h6><?= Status::getExamStatus($student->exam_type); ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Imtixon holati</p>
                        <h6>
                            <?php
                            switch ($exam->status) {
                                case 1:
                                    echo "Imtixonni boshlamadingiz";
                                    break;
                                case 2:
                                    echo "Imtixonni boshladingiz";
                                    break;
                                case 3:
                                    echo "Imtixondan o'tdingiz";
                                    break;
                                case 4:
                                    echo "Imtixondan o'ta olmadingiz";
                                    break;
                                default:
                                    echo "---";
                                    break;
                            }
                            ?>
                        </h6>
                    </div>
                </div>

                <?php if ($exam->status >= 3): ?>
                    <div class="col-md-4 col-12 mb-4">
                        <div class="ika_column">
                            <p>To'plangan ball</p>
                            <h6><?= $exam->ball ?> ball</h6>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p>Imtixonga ajratilgan vaqt</p>
                        <h6>
                            1 soat 20 minut
                        </h6>
                    </div>
                </div>

                <?php if (!empty($subjects)) : ?>
                    <div class="col-md-12">
                        <div class="ika_column">
                            <p>Fanlari</p>
                            <div class="row mt-2">
                                <?php foreach ($subjects as $subject) : ?>
                                    <?php $directionSubject = $subject->directionSubject; ?>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="ika_user_page_item">
                                            <ul>
                                                <li>Fan nomi:</li>
                                                <li>
                                                    <p><?= $subject->subject['name_' . $lang] ?? "-" ?></p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>Jami savollar soni:</li>
                                                <li>
                                                    <p><?= $directionSubject->count ?? "0" ?> ta</p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>Har bir savolga beriladigan ball:</li>
                                                <li>
                                                    <p><?= $directionSubject->ball ?? "0" ?> ball</p>
                                                </li>
                                            </ul>
                                            <?php if ($exam->status > 3) : ?>
                                                <ul>
                                                    <li>Fandan to'plagan ball:</li>
                                                    <li>
                                                        <p><?= $subject->ball ?> ball</p>
                                                    </li>
                                                </ul>
                                            <?php endif; ?>
                                            <ul>
                                                <li>Fandan sertifikat:</li>
                                                <li>
                                                    <p>
                                                        <?php
                                                        $statuses = [
                                                            0 => "Yuklanmagan",
                                                            1 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">Tekshirilmoqda <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                            2 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">Tasdiqlandi <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                            3 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">Bekor qilindi <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                        ];
                                                        echo $statuses[$subject->file_status] ?? "Noma'lum holat";
                                                        ?>
                                                    </p>
                                                </li>
                                            </ul>

                                            <?php if ($subject->file_status == 0) : ?>
                                                <div class="ika_user_page_button">
                                                    <?php
                                                    $url = Url::to(['file/create-sertificate', 'id' => $subject->id]);
                                                    echo Html::a('<span>' . Yii::t("app", "a101") . '</span><i class="bi bi-arrow-up-circle"></i>', $url, [
                                                        "data-bs-toggle" => "modal",
                                                        "data-bs-target" => "#studentModalUpload",
                                                    ]);
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($exam->status < 3) : ?>
                    <?php if ($is_exam) : ?>
                        <?php if ($student->ipCheck) : ?>
                            <div class="d-flex justify-content-center top30">
                                <a href="<?= Url::to(['cabinet/test']) ?>" class="linkExam">
                                    <span>
                                        <?php if ($exam->status == 1) : ?>
                                            <?= Yii::t("app", "a130") ?>
                                        <?php elseif ($exam->status == 2) : ?>
                                            <?= Yii::t("app", "a131") ?>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="col-md-12 col-12">
                                <div class="ika_danger top30">
                                    <h6><i class="fa-solid fa-exclamation"></i> <span><?= getIpMK() ?> sizning qurilmangizga imtixonda qatnashishga ruxsat berilmagan.</span></h6>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="col-md-12 col-12">
                            <div class="ika_danger top30">
                                <h6><i class="fa-solid fa-exclamation"></i> <span>Imtixonda qatnashish uchun 5 yillik staj fayl tasdiqlanishini kuting.</span></h6>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($eduDirection->is_oferta == 1) : ?>
            <div class="ikpage top30">
                <div class="htitle">
                    <h6><?= Yii::t("app", "a127") ?></h6>
                    <span></span>
                </div>
                <div class="row top30">
                    <div class="col-lg-6 col-md-12">
                        <div class="cfile_box">
                            <div class="cfile_box_head_right <?= ($oferta->file_status == 0 || $oferta->file_status == 3) ? 'danger' : (($oferta->file_status == 2) ? 'active' : '') ?>">
                                <p><?= Yii::t("app", "a" . (82 + $oferta->file_status)) ?></p>
                            </div>
                            <div class="cfile_box_head">
                                <div class="cfile_box_head_left">
                                    <h5>&nbsp; <span></span> &nbsp;&nbsp; <?= Yii::t("app", "a127") ?></h5>
                                </div>
                            </div>
                            <div class="cfile_box_content_question">
                                <p><span><i class="fa-solid fa-exclamation"></i></span>
                                    Prezidentning 2022-yil 22-iyundagi PQ-289-son qaroriga muvofiq pedagogika sohasidagi ta’lim yo‘nalishlariga sirtqi ta’lim shakli bo‘yicha o‘qishga ta’lim tizimida pedagogik faoliyatga oid kamida besh yillik ish stajiga ega bo‘lgan shaxslar qabul qilinadi. </p>
                            </div>
                            <?php if ($oferta->file_status == 0) : ?>
                                <div class="cfile_box_content_upload">
                                    <?= Html::a(Yii::t("app", "a128"), Url::to(['file/create-oferta', 'id' => $oferta->id]), [
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#studentModalUpload"
                                    ]) ?>
                                </div>
                            <?php else: ?>
                                <div class="cfile_box_content">
                                    <div class="cfile_box_content_file">
                                        <div class="cfile_box_content_file_left">
                                            <a href="/frontend/web/uploads/<?= $oferta->student_id ?>/<?= $oferta->file ?>" target="_blank">
                                                <span><i class="fa-solid fa-file-export"></i></span> <?= Yii::t("app", "a89") ?>
                                            </a>
                                        </div>
                                        <?php if ($oferta->file_status != 2) : ?>
                                            <div class="cfile_box_content_file_right">
                                                <?= Html::a('<i class="fa-solid fa-trash"></i>', Url::to(['file/del-oferta', 'id' => $oferta->id]), [
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentModalDelete"
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="studentModalUpload" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" id="modalUploadBody"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="studentModalDelete" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" id="modalDeleteBody"></div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
$(document).ready(function() {
    $('#studentModalUpload, #studentModalDelete').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('.modal-body').load(url);
    });
});
JS;
$this->registerJs($js);
?>