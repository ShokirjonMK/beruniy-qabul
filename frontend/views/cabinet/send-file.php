<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Student;
use common\models\StudentPerevot;
use common\models\StudentOferta;
use common\models\StudentDtm;
use common\models\StudentMaster;

/** @var Student $student */

$lang = Yii::$app->language;
$this->title = Yii::t("app", "a44");
$eduDirection = $student->eduDirection;

$documents = [];

if ($student->edu_type_id == 2) {
    $documents[] = [
        'model' => StudentPerevot::findOne([ 'edu_direction_id' => $eduDirection->id, 'student_id' => $student->id, 'status' => 1, 'is_deleted' => 0 ]),
        'title' => Yii::t("app", "a86"),
        'button' => Yii::t("app", "a88"),
        'upload_url' => 'file/create-tr',
        'delete_url' => 'file/del-tr',
        'text' => false
    ];
} elseif ($student->edu_type_id == 3) {
    $documents[] = [
        'model' => StudentDtm::findOne([ 'edu_direction_id' => $eduDirection->id, 'student_id' => $student->id, 'status' => 1, 'is_deleted' => 0 ]),
        'title' => Yii::t("app", "a148"),
        'button' => Yii::t("app", "a150"),
        'upload_url' => 'file/create-dtm',
        'delete_url' => 'file/del-dtm',
        'text' => false
    ];
} elseif ($student->edu_type_id == 4) {
    $documents[] = [
        'model' => StudentMaster::findOne([ 'edu_direction_id' => $eduDirection->id, 'student_id' => $student->id, 'status' => 1, 'is_deleted' => 0 ]),
        'title' => Yii::t("app", "a162"),
        'button' => Yii::t("app", "a164"),
        'upload_url' => 'file/create-master',
        'delete_url' => 'file/del-master',
        'text' => false
    ];
}

if ($eduDirection->is_oferta == 1) {
    $documents[] = [
        'model' => StudentOferta::findOne([ 'edu_direction_id' => $eduDirection->id, 'student_id' => $student->id, 'status' => 1, 'is_deleted' => 0 ]),
        'title' => Yii::t("app", "a127"),
        'button' => Yii::t("app", "a128"),
        'upload_url' => 'file/create-oferta',
        'delete_url' => 'file/del-oferta',
        'text' => 'Prezidentning 2022-yil 22-iyundagi PQ-289-son qaroriga muvofiq pedagogika sohasidagi ta’lim yo‘nalishlariga sirtqi ta’lim shakli bo‘yicha o‘qishga ta’lim tizimida pedagogik faoliyatga oid kamida besh yillik ish stajiga ega bo‘lgan shaxslar qabul qilinadi.'
    ];
}

function renderDocumentBox($document) {
    if (!$document['model']) return;
    ?>
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="cfile_box">
            <div class="cfile_box_head_right <?= ($document['model']->file_status == 0 || $document['model']->file_status == 3) ? 'danger' : (($document['model']->file_status == 2) ? 'active' : '') ?>">
                <p><?= Yii::t("app", "a" . (82 + $document['model']->file_status)) ?></p>
            </div>
            <div class="cfile_box_head">
                <div class="cfile_box_head_left">
                    <h5><span></span> <?= $document['title'] ?></h5>
                </div>
            </div>
            <?php if ($document['text']) : ?>
                <div class="cfile_box_content_question">
                    <p>
                        <span><i class="fa-solid fa-exclamation"></i></span>
                        <?= $document['text'] ?>
                    </p>
                </div>
            <?php endif; ?>
            <?php if ($document['model']->file_status == 0) : ?>
                <div class="cfile_box_content_upload">
                    <?= Html::a($document['button'], Url::to([$document['upload_url'], 'id' => $document['model']->id]), [
                        "data-bs-toggle" => "modal",
                        "data-bs-target" => "#studentModalUpload"
                    ]) ?>
                </div>
            <?php else: ?>
                <div class="cfile_box_content">
                    <div class="cfile_box_content_file">
                        <div class="cfile_box_content_file_left">
                            <a href="/frontend/web/uploads/<?= $document['model']->student_id ?>/<?= $document['model']->file ?>" target="_blank">
                                <span><i class="fa-solid fa-file-export"></i></span> <?= Yii::t("app", "a89") ?>
                            </a>
                        </div>
                        <?php if ($document['model']->file_status != 2) : ?>
                            <div class="cfile_box_content_file_right">
                                <?= Html::a('<i class="fa-solid fa-trash"></i>', Url::to([$document['delete_url'], 'id' => $document['model']->id]), [
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
    <?php
}
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a44") ?></h6>
                <span></span>
            </div>
            <?php if (count($documents) > 0) : ?>
                <div class="row top40">
                    <?php foreach ($documents as $document) renderDocumentBox($document); ?>
                </div>
            <?php else: ?>
                <div class="down_box top30">
                    <div class="page_notfound">
                        <p align="center">
                            <svg  viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(24 31.67)"><ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse><path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path><path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path><path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path><path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path></g><path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path><g transform="translate(149.65 15.383)" fill="#FFF"><ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse><path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path></g></g></svg>
                        </p>
                        <br>
                        <p align="center">Yuborilgan arizalar mavjud emas.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
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
