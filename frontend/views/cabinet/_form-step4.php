<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Languages;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\Status;

/** @var $model */
/** @var Student $student */
/** @var $id */
?>

<div class="step_confirm">

    <?php if ($student->edu_type_id == 1): ?>
        <?= $this->render('_qabul' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php elseif ($student->edu_type_id == 2): ?>
        <?= $this->render('_perevot', [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php elseif ($student->edu_type_id == 3): ?>
        <?= $this->render('dtm', [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php elseif ($student->edu_type_id == 4): ?>
        <?= $this->render('master', [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php endif;  ?>

    <div class="down_box top30">
        <div class="down_title">
            <h6><i class="fa-solid fa-user-check"></i> &nbsp;&nbsp; <?= Yii::t("app" , "a65") ?></h6>
        </div>
        <div class="down_content">

            <div class="down_content_box">
                <div class="down_content_box_left">
                    <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div class="down_content_box_right">
                    <p><?= Yii::t("app" , "a66") ?></p>
                    <h6><?= $student->last_name ?></h6>
                </div>
            </div>

            <div class="down_content_box">
                <div class="down_content_box_left">
                    <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div class="down_content_box_right">
                    <p><?= Yii::t("app" , "a67") ?></p>
                    <h6><?= $student->first_name ?></h6>
                </div>
            </div>

            <div class="down_content_box">
                <div class="down_content_box_left">
                    <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div class="down_content_box_right">
                    <p><?= Yii::t("app" , "a67") ?></p>
                    <h6><?= $student->middle_name ?></h6>
                </div>
            </div>

            <div class="down_content_box">
                <div class="down_content_box_left">
                    <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div class="down_content_box_right">
                    <p><?= Yii::t("app" , "a69") ?></p>
                    <h6><?= $student->birthday ?></h6>
                </div>
            </div>

            <div class="down_content_box">
                <div class="down_content_box_left">
                    <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div class="down_content_box_right">
                    <p><?= Yii::t("app" , "a70") ?></p>
                    <h6><?= $student->passport_serial.' '.$student->passport_number ?></h6>
                </div>
            </div>

        </div>
    </div>

    <?php if ($student->edu_type_id == 2) : ?>

        <div class="modal fade" id="perOfertaModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="perOferta">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'top40'],
                            'fieldConfig' => [
                                'template' => '{label}{input}{error}',
                            ]
                        ]); ?>

                        <div class="alert_question">
                            <div class="alert_danger_circle">
                                <div class="alert_danger_box">
                                    <i class="fa-solid fa-question"></i>
                                </div>
                            </div>
                            <p style="text-transform: unset">
                                <?= Yii::t("app" , "a71") ?>
                            </p>
                            <p>
                                <?= Yii::t("app" , "a72") ?>
                            </p>
                        </div>

                        <div class="form-group d-none">
                            <?= $form->field($model, 'check')->textInput(); ?>
                        </div>


                        <div class="d-flex justify-content-around align-items-center top30">
                            <?= Html::button(Yii::t("app" , "a73"), ['class' => 'step_left_btn step_btn', 'data-bs-dismiss' => 'modal']) ?>
                            <?= Html::submitButton(Yii::t("app" , "a37"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="step_btn_block">
            <button type="button" class="step_right_btn step_btn" data-bs-toggle="modal" data-bs-target="#perOfertaModal">
                <?= Yii::t("app" , "a37") ?>
            </button>
        </div>

    <?php else: ?>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'top40'],
            'fieldConfig' => [
                'template' => '{label}{input}{error}',
            ]
        ]); ?>

        <div class="form-group d-none">
            <?= $form->field($model, 'check')->textInput(); ?>
        </div>

        <div class="step_btn_block top30">
            <?= Html::submitButton(Yii::t("app" , "a37"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>


</div>




