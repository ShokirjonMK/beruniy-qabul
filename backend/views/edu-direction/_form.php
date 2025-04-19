<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use common\models\Direction;
use common\models\EduType;
use common\models\EduForm;
use common\models\Lang;
use yii\helpers\ArrayHelper;
use common\models\Branch;

/** @var yii\web\View $this */
/** @var common\models\EduDirection $model */
/** @var yii\widgets\ActiveForm $form */
$directions = [];
if ($model->branch_id != null) {
    $directions = Direction::find()
        ->where(['is_deleted' => 0, 'branch_id' => $model->branch_id, 'status' => 1])->all();
    $directions = ArrayHelper::map($directions, 'id', 'name');
}
$langs = Lang::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduTypes = EduType::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduForms = EduForm::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$examTypes = [0, 1];
?>

<div class="edu-direction-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-12 col-lg-10">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="row">
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class='form-group'>
                                <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
                                    'options' => ['placeholder' => 'Filial tanlang'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Filial tanlang'); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'direction_id')->widget(Select2::classname(), [
                                    'data' => $directions,
                                    'options' => ['placeholder' => 'Yo\'nalishlar'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Yo\'nalishlar'); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'edu_type_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($eduTypes, 'id', 'name_uz'),
                                    'options' => ['placeholder' => 'Ta\'lim turi'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Ta\'lim turi'); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'edu_form_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($eduForms, 'id', 'name_uz'),
                                    'options' => ['placeholder' => 'Ta\'lim shakli'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Ta\'lim shakli'); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'lang_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($langs, 'id', 'name_uz'),
                                    'options' => ['placeholder' => 'Ta\'lim tili'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Ta\'lim tili'); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'is_oferta')->widget(Select2::classname(), [
                                    'data' => Status::ofertaStatus(),
                                    'options' => ['placeholder' => 'Oferta status tanlang ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-6 col-lg-4'>
                            <div class="form-group">
                                <?= $form->field($model, 'status')->widget(Select2::classname(), [
                                    'data' => Status::accessStatus(),
                                    'options' => ['placeholder' => 'Status tanlang ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-2">
            <div class="form-section">
                <div class="form-section_item">
                    <?php foreach ($examTypes as $type) : ?>
                        <?php
                            $check = false;
                            if ($model->exam_type != null) {
                                $examValue =json_decode($model->exam_type, true);
                                if (in_array($type , $examValue)) {
                                    $check = true;
                                }
                            }
                        ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="subject_box">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="bu-check" name="exam[<?= $type ?>]" id="check_<?= $type ?>" value="<?= $type ?>" <?php if ($check) { echo "checked";} ?>>
                                        <label for="check_<?= $type ?>" class="permission_label"><?= Status::getExamStatus($type) ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-3 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#edudirection-branch_id").on('change', function () {
            var branch_id = $(this).val();
            if (branch_id > 0) {
                $.ajax({
                    url: '../file/direction-branch/',
                    data: {branch_id: branch_id},
                    type: 'GET',
                    success: function (data) {
                        $("#edudirection-direction_id").html(data);
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
