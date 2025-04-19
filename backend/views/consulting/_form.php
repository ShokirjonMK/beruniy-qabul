<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use common\models\Branch;
use common\models\ConsultingBranch;

$branchs = Branch::find()
    ->where(['status' => 1, 'is_deleted' => 0])
    ->all();
/** @var yii\web\View $this */
/** @var common\models\Consulting $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulting-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row justify-content-between">
        <div class="col-12 col-md-6">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class='form-group'>
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'hr')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_name_uz')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_name_ru')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_name_en')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_adress_uz')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_adress_ru')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'bank_adress_en')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class='form-group'>
                                <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'mfo')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'tel1')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask' => '+\9\9\8 (99) 999-99-99',
                                        'options' => [
                                            'placeholder' => '+998 (__) ___-__-__',
                                        ],
                                    ]) ?>
                            </div>

                            <div class='form-group'>
                                <?= $form->field($model, 'tel2')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask' => '+\9\9\8 (99) 999-99-99',
                                        'options' => [
                                            'placeholder' => '+998 (__) ___-__-__',
                                        ],
                                    ]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'domen')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
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
        <div class="col-12 col-md-5">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="form-group">
                        <div class="col-md-6 col-6">
                            <div class="view-info-right">
                                <h6>Filiallar</h6>
                            </div>
                        </div>

                        <?php foreach ($branchs as $branch) : ?>
                            <?php
                            $isBranch = ConsultingBranch::findOne([
                                'branch_id' => $branch->id,
                                'consulting_id' => $model->id,
                                'status' => 1,
                                'is_deleted' => 0
                            ]);
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="subject_box">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="bu-check" name="filial[<?= $branch->id ?>]" id="check_<?= $branch->id ?>" <?php if ($isBranch) { echo "checked";} ?> value="1">
                                            <label for="check_<?= $branch->id ?>" class="permission_label"><?= $branch->name_uz ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
