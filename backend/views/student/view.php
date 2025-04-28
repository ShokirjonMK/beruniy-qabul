<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\StudentOferta;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentMaster;
use common\models\StudentDtm;
use common\models\Course;
use yii\helpers\Url;


/** @var yii\web\View $this */
/** @var common\models\Student $model */

$cons = $model->user->cons;
$user = $model->user;
$eduDirection = $model->eduDirection;
$direction = $eduDirection->direction ?? false;
$this->title = 'Ma\'lumotlar tahlili';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
if ($model->edu_type_id != null) {
    $breadcrumbs['item'][] = [
        'label' => $model->eduType->name_uz,
        'url' => ['index' , 'id' => $model->edu_type_id],
    ];
} else {
    $breadcrumbs['item'][] = [
        'label' => 'Chala arizalar',
        'url' => ['chala'],
    ];
}
$contract = false;
$contract_price = 0;
$cont = null;
$modelsMap = [
    1 => Exam::class,
    2 => StudentPerevot::class,
    3 => StudentDtm::class,
    4 => StudentMaster::class,
];

if ($model->edu_type_id !== null && isset($modelsMap[$model->edu_type_id])) {
    $eduModel = $modelsMap[$model->edu_type_id]::findOne([
        'student_id' => $model->id,
        'edu_direction_id' => $model->edu_direction_id,
        'is_deleted' => 0
    ]);

    if ($eduModel) {
        if ($model->edu_type_id == 1) {
            $examSubjects = $eduModel->examSubjects;
            if ($eduModel->status == 3) {
                $cont = $eduModel->id;
                $contract = true;
                $contract_price = $eduModel->contract_price;
            }
        } elseif ($eduModel->file_status == 2) {
            $cont = $eduModel->id;
            $contract = true;
            $contract_price = $eduModel->contract_price;
        }
    }
}
if ($eduDirection) {
    if ($eduDirection->is_oferta == 1) {
        $oferta = StudentOferta::findOne([
            'edu_direction_id' => $eduDirection->id,
            'student_id' => $model->id,
            'status' => 1,
            'is_deleted' => 0
        ]);
        if ($oferta->file_status != 2) {
            $contract = false;
        }
    }
}
$eduType = false;
if ($model->eduType != null) {
    $eduType = $model->eduType;
}

\yii\web\YiiAsset::register($this);
?>
<div class="page">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbs['item'] as $item) : ?>
            <li class='breadcrumb-item'>
                <?= Html::a($item['label'], $item['url'], ['class' => '']) ?>
            </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <div class="page-item mb-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="view-info-right">
                                    <div class="subject_box">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="subject_box_left">
                                                <p>Student id:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6>ID: <?= $model->user_id ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Lead id:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6>ID: <?= $user->lead_id ?? 'Mavjud emas'?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Telefon raqam:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $user->username ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Parol:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->password ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Status:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->userStatus ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Raqam ro'yhatga olingan sana:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= date("Y-m-d  H:i:s" , $model->user->created_at) ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Qaysi oqimdan kelganligi:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6>
                                                    <?= $cons->name ?> &nbsp;&nbsp;
                                                    <?= "<a href='https://{$cons->domen}'>".$cons->domen."</a>"; ?>
                                                </h6>
                                            </div>
                                        </div>

<!--                                        <div class="d-flex justify-content-between align-items-center mt-3">-->
<!--                                            <div class="subject_box_left">-->
<!--                                                <p>Bot status:</p>-->
<!--                                            </div>-->
<!--                                            <div class="subject_box_right">-->
<!--                                                <h6>--><?php //= $user->telegram_id ? 'Mavjud' : 'Mavjud emas' ?><!--</h6>-->
<!--                                            </div>-->
<!--                                        </div>-->

                                        <?php if ($user->step == 5) : ?>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div class="subject_box_left">
                                                    <p>Jarayoni:</p>
                                                </div>
                                                <div class="subject_box_right">
                                                    <h6><?= $model->educationStatus ?></h6>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div class="subject_box_left">
                                                    <p>Qadam:</p>
                                                </div>
                                                <div class="subject_box_right">
                                                    <h6><?= $user->step ?> - qadam</h6>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex gap-3 align-items-center mt-3">
                                            <?php if (permission('student', 'user-update')): ?>
                                                <?= Html::a(Yii::t('app', 'Tahrirlash'), ['student/user-update', 'id' => $model->id],
                                                    [
                                                        'class' => 'sub_links',
                                                        "data-bs-toggle" => "modal",
                                                        "data-bs-target" => "#studentInfo",
                                                    ])
                                                ?>
                                            <?php endif; ?>

                                            <?php if (permission('student', 'send-sms')): ?>
                                                <?= Html::a(Yii::t('app', 'SMS habar yuborish'), ['student/send-sms', 'id' => $model->id],
                                                    [
                                                        'class' => 'sub_links',
                                                        "data-bs-toggle" => "modal",
                                                        "data-bs-target" => "#studentInfo",
                                                    ])
                                                ?>
                                            <?php endif; ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-item mb-4">

        <div class="page_title mt-5 mb-3">
            <h6 class="title-h5">Pasport ma'lumoti</h6>
            <?php if (permission('student', 'info')): ?>
                <h6 class="title-link">
                    <?= Html::a(Yii::t('app', 'Tahrirlash'), ['info', 'id' => $model->id],
                        [
                            "data-bs-toggle" => "modal",
                            "data-bs-target" => "#studentInfoDate",
                        ])
                    ?>
                </h6>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <?php if ($model->passport_pin != null) : ?>
                            <div class="row">

                                <div class="col-md-3 col-12">
                                    <div class="view-info-right">
                                        <p>F.I.O</p>
                                        <h6><?= $model->fullName ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="view-info-right">
                                        <p>Tug'ilgan sana</p>
                                        <h6><?= $model->birthday ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="view-info-right">
                                        <p>Pasport ma'lumoti</p>
                                        <h6><?= $model->passport_serial.' '.$model->passport_number ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="view-info-right">
                                        <p>JShShIR</p>
                                        <h6><?= $model->passport_pin ?></h6>
                                    </div>
                                </div>

                            </div>
                        <?php else: ?>
                            <p align="center" class="svg_icon">
                                <svg  viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(24 31.67)"><ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse><path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path><path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path><path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path><path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path></g><path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path><g transform="translate(149.65 15.383)" fill="#FFF"><ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse><path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path></g></g></svg>
                            </p>
                            <br>
                            <p align="center">Pasport ma'lumotlari mavjud emas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-item mb-4">
        <div class="page_title mt-5 mb-3">
            <h6 class="title-h5">Qabul turi</h6>
            <?php if (permission('student', 'edu-type')): ?>
                <h6 class="title-link">
                    <?= Html::a(Yii::t('app', 'Tahrirlash'), ['edu-type', 'id' => $model->id],
                        ["data-bs-toggle" => "modal", "data-bs-target" => "#studentInfo"])
                    ?>
                </h6>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <?php if ($eduType) : ?>
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="view-info-right">
                                        <p>Qabul turi</p>
                                        <h6><?= $eduType->name_uz ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <p align="center" class="svg_icon">
                                <svg  viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(24 31.67)"><ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse><path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path><path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path><path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path><path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path></g><path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path><g transform="translate(149.65 15.383)" fill="#FFF"><ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse><path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path></g></g></svg>
                            </p>
                            <br>
                            <p align="center">Qabul ma'lumotlari mavjud emas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($eduDirection): ?>
        <?php if ($eduDirection->is_oferta == 1): ?>
            <div class="page-item mb-4">
                <div class="page_title mt-5 mb-3">
                    <h6 class="title-h5">Oferta ma'lumoti</h6>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-section">
                            <div class="form-section_item">
                                <div class="subject_box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="subject_box_left">
                                            <p>Holati:</p>
                                        </div>
                                        <div class="subject_box_right">
                                            <h6>
                                                <?php if ($oferta->file_status == 0) : ?>
                                                    Yuklanmagan
                                                <?php elseif ($oferta->file_status == 1): ?>
                                                    <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Kelib tushdi</a>
                                                <?php elseif ($oferta->file_status == 2): ?>
                                                    <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Tasdiqlandi</a>
                                                <?php elseif ($oferta->file_status == 3): ?>
                                                    <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Bekor qilindi</a>
                                                <?php endif; ?>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 align-items-center mt-3">
                                        <?php if (permission('student', 'oferta-upload')): ?>
                                            <?= Html::a(Yii::t('app', 'Oferta yuklash'), ['student/oferta-upload', 'id' => $oferta->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentInfo",
                                                ])
                                            ?>
                                        <?php endif; ?>

                                        <?php if (permission('student', 'oferta-confirm')): ?>
                                            <?= Html::a(Yii::t('app', 'Oferta tasdiqlash'), ['student/oferta-confirm', 'id' => $oferta->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentInfo",
                                                ]) ?>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="page-item mb-4">
        <div class="page_title mt-5 mb-3">
            <h6 class="title-h5"><?php if ($eduType) { echo $eduType->name_uz; } else { echo "Chala user";} ?> ma'lumotlari</h6>
            <div class="d-flex gap-3">
                <?php if (isset($eduModel) && $eduType->id == 1 && $eduModel->status == 4) : ?>
                    <?php if (permission('student', 'exam-change')): ?>
                        <h6 class="title-link">
                            <?= Html::a(Yii::t('app', 'Qayta imkon berish'), ['exam-change', 'id' => $model->id],
                                [
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Rostdan ham testga qayta imkon bermoqchimisiz?'),
                                    ],
                                ])
                            ?>
                        </h6>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (permission('student', 'direction')): ?>
                    <h6 class="title-link">
                        <?= Html::a(Yii::t('app', 'Tahrirlash'), ['direction', 'id' => $model->id],
                            ["data-bs-toggle" => "modal", "data-bs-target" => "#studentInfo"])
                        ?>
                    </h6>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <?php if ($eduDirection): ?>
                            <div class="row">

                                <div class="col-md-4 col-12 mb-4">
                                    <div class="view-info-right">
                                        <p>Filial</p>
                                        <h6><?= $model->branch->name_uz ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12 mb-4">
                                    <div class="view-info-right">
                                        <p>Yo'nalish nomi</p>
                                        <h6><?= $direction->code.' - '.$direction->name_uz ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12  mb-4">
                                    <div class="view-info-right">
                                        <p>Ta'lim tili</p>
                                        <h6><?= $eduDirection->lang->name_uz ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12 mb-4">
                                    <div class="view-info-right">
                                        <p>Ta'lim shakli</p>
                                        <h6><?= $eduDirection->eduForm->name_uz ?></h6>
                                    </div>
                                </div>

                                <?php if ($model->edu_type_id == 2) : ?>

                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Avvalgi OTM nomi</p>
                                            <h6><?= $model->edu_name ?></h6>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Tamomlagan bosqich</p>
                                            <h6><?= Course::findOne(['id' => $model->course_id])->name_uz ?></h6>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($eduModel): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($eduModel->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($eduModel->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($eduModel->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($eduModel->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?php if (permission('student', 'tr-upload')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Transkript yuklash'), ['student/tr-upload', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ])
                                                                    ?>
                                                                <?php endif; ?>

                                                                <?php if (permission('student', 'tr-confirm')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Transkript tasdiqlash'), ['student/tr-confirm', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ]) ?>
                                                                <?php endif; ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                <?php elseif ($model->edu_type_id == 1) : ?>
                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Imtixon turi</p>
                                            <h6>
                                                <?php if ($model->exam_type == 0) : ?>
                                                    Online
                                                <?php elseif ($model->exam_type == 1): ?>
                                                    Offline
                                                <?php endif; ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Holati</p>
                                            <h6>
                                                <?php if ($eduModel->status == 0) : ?>
                                                    Bekor qilingan
                                                <?php elseif ($eduModel->status == 1): ?>
                                                    Testga kirmagan
                                                <?php elseif ($eduModel->status == 2): ?>
                                                    Test ishlamoqda
                                                <?php elseif ($eduModel->status == 3): ?>
                                                    Testni yakunlab shartnoma tasdiqlandi
                                                <?php elseif ($eduModel->status == 3): ?>
                                                    Testni yakunladi
                                                <?php endif; ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <?php if ($eduModel->status == 3) : ?>
                                        <div class="col-md-4 col-12">
                                            <div class="view-info-right">
                                                <p>To'plangan ball</p>
                                                <h6>
                                                    <?= $eduModel->ball ?? '0' ?> ball
                                                </h6>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Fanlari</p>
                                            <?php if (count($examSubjects) > 0) : ?>
                                                <div class="row mt-2">
                                                    <?php foreach ($examSubjects as $examSubject) : ?>
                                                        <div class="col-lg-6 col-md-12">
                                                            <div class="subject_box">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div class="subject_box_left">
                                                                        <p>Fan nomi:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->subject->name_uz ?></h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Jami savollar soni:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->directionSubject->count ?> ta</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Har bir savolga beriladigan ball:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->directionSubject->ball ?> ball</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Fandan to'plangan ball:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->ball ?? '0' ?> ball</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Sertifikat:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6>
                                                                            <?php if ($examSubject->file_status == 0) : ?>
                                                                                Yuklanmagan
                                                                            <?php elseif ($examSubject->file_status == 1): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Kelib tushdi</a>
                                                                            <?php elseif ($examSubject->file_status == 2): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Tasdiqlandi</a>
                                                                            <?php elseif ($examSubject->file_status == 3): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Bekor qilindi</a>
                                                                            <?php endif; ?>
                                                                        </h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex gap-3 align-items-center mt-3">
                                                                    <?php if (permission('student', 'sertificate-upload')): ?>
                                                                        <?= Html::a(Yii::t('app', 'Sertifikat yuklash'), ['student/sertificate-upload', 'id' => $examSubject->id],
                                                                            [
                                                                                'class' => 'sub_links',
                                                                                "data-bs-toggle" => "modal",
                                                                                "data-bs-target" => "#studentInfo",
                                                                            ])
                                                                        ?>
                                                                    <?php endif; ?>

                                                                    <?php if (permission('student', 'sertificate-confirm')): ?>
                                                                        <?= Html::a(Yii::t('app', 'Sertifikat tasdiqlash'), ['student/sertificate-confirm', 'id' => $examSubject->id],
                                                                            [
                                                                                'class' => 'sub_links',
                                                                                "data-bs-toggle" => "modal",
                                                                                "data-bs-target" => "#studentInfo",
                                                                            ]) ?>
                                                                    <?php endif; ?>

                                                                    <?php if (permission('student', 'add-ball')): ?>
                                                                        <?= Html::a(Yii::t('app', 'Ball berish'), ['student/add-ball', 'id' => $examSubject->id],
                                                                            [
                                                                                'class' => 'sub_links',
                                                                                "data-bs-toggle" => "modal",
                                                                                "data-bs-target" => "#studentInfo",
                                                                            ]) ?>
                                                                    <?php endif; ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php elseif ($model->edu_type_id == 3) : ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($eduModel): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($eduModel->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($eduModel->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($eduModel->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($eduModel->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?php if (permission('student', 'dtm-upload')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Fayl yuklash'), ['student/dtm-upload', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ])
                                                                    ?>
                                                                <?php endif; ?>

                                                                <?php if (permission('student', 'dtm-confirm')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Faylni tasdiqlash'), ['student/dtm-confirm', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ]) ?>
                                                                <?php endif; ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($model->edu_type_id == 4) : ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($eduModel): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($eduModel->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($eduModel->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($eduModel->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($eduModel->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $eduModel->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?php if (permission('student', 'master-upload')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Fayl yuklash'), ['student/master-upload', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ])
                                                                    ?>
                                                                <?php endif; ?>


                                                                <?php if (permission('student', 'master-confirm')): ?>
                                                                    <?= Html::a(Yii::t('app', 'Faylni tasdiqlash'), ['student/master-confirm', 'id' => $eduModel->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentInfo",
                                                                        ]) ?>
                                                                <?php endif; ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p align="center" class="svg_icon">
                                <svg  viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(24 31.67)"><ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse><path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path><path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path><path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path><path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path></g><path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path><g transform="translate(149.65 15.383)" fill="#FFF"><ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse><path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path></g></g></svg>
                            </p>
                            <br>
                            <p align="center"><?php if ($eduType) { echo $eduType->name_uz; } else { echo "Chala user";} ?> ma'lumotlari mavjud emas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php if ($contract) : ?>

        <div class="page-item mb-4">
            <div class="page_title mt-5 mb-3">
                <h6 class="title-h5">Shartnoma</h6>
                <?php if (permission('student', 'contract-update')): ?>
                    <h6 class="title-link">
                        <?= Html::a(Yii::t('app', 'Tahrirlash'), ['contract-update',  'id' => $cont , 'type' => $model->edu_type_id , 'std_id' => $model->id],
                            [
                                "data-bs-toggle" => "modal",
                                "data-bs-target" => "#studentInfo",
                            ])
                        ?>
                    </h6>
                <?php endif; ?>
            </div>
            <?php if (permission('student', 'contract-load')): ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-section">
                            <div class="form-section_item">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="view-info-right">
                                            <p>Ikki tomonlama shartnoma &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= number_format((int)$contract_price, 0, '', ' ') .' so‘m' ?> </p>
                                            <h6><a href="<?= Url::to(['student/contract-load' , 'id' => $model->id , 'type' => 2]) ?>">Yuklash uchun bosing</a></h6>
                                        </div>
                                    </div>
                                    <!--                                <div class="col-md-6 col-12">-->
                                    <!--                                    <div class="view-info-right">-->
                                    <!--                                        <p>Uch tomonlama shartnoma &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; --><?php //= number_format((int)$contract_price, 0, '', ' ') .' so‘m' ?><!--</p>-->
                                    <!--                                        <h6>-->
                                    <!--                                            <h6><a href="--><?php //= Url::to(['student/contract-load' , 'id' => $model->id , 'type' => 3]) ?><!--">Yuklash uchun bosing</a></h6>-->
                                    <!--                                        </h6>-->
                                    <!--                                    </div>-->
                                    <!--                                </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="studentInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-body" id="studentInfoBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentInfoDate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-body" id="studentInfoBodyDate">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    $('#studentInfo').on('show.bs.modal', function (e) {
        $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBody').load(url);
    });
    
    $('#studentInfoDate').on('show.bs.modal', function (e) {
        // $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBodyDate').load(url);
    });
});
JS;
$this->registerJs($js);
?>
