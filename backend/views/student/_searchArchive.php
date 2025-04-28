<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Languages;
use common\models\Direction;
use common\models\Status;
use kartik\date\DatePicker;
use common\models\EduType;
use common\models\EduForm;
use common\models\EduDirection;
use common\models\Lang;
use common\models\Branch;
use common\models\Consulting;

/** @var yii\web\View $this */
/** @var common\models\StudentPerevotSearch $model */
/** @var yii\widgets\ActiveForm $form */
/** @var \common\models\EduType $eduType */

$branchs = Branch::find()
    ->where(['in', 'id', getBranchOneIk()])
    ->all();

$cons = Consulting::find()
    ->where(['in', 'id', getConsOneIk()])
    ->all();

?>

<div class="student-perevot-search">
    <?php $form = ActiveForm::begin([
        'action' => ['all'],
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-4">
        <div class="form-section_item">

            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'first_name') ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'last_name') ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'middle_name') ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'username')
                            ->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '+\9\9\8 (99) 999-99-99',
                                'options' => [
                                    'placeholder' => '+998 (__) ___-__-__',
                                ],
                            ]) ?>
                    </div>
                </div>
                <div class="col-lg-1 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_serial')->textInput([
                            'maxlength' => true,
                            'placeholder' => '__',
                            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_number')->widget(\yii\widgets\MaskedInput::class, [
                            'mask' => '9999999',
                            'options' => [
                                'placeholder' => '_______',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'user_status')->widget(Select2::classname(), [
                            'data' => Status::userStatusUpdate(),
                            'options' => ['placeholder' => 'Status tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('User status <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                            'data' =>  ArrayHelper::map($branchs, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Filial tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Filial <span>*</span>');; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'cons_id')->widget(Select2::classname(), [
                            'data' =>  ArrayHelper::map($cons, 'id', 'name'),
                            'options' => ['placeholder' => 'Xamkorlar tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Xamkorlar <span>*</span>');; ?>
                    </div>
                </div>

            </div>

            <div class="form-group d-flex justify-content-end gap-2">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'b-btn b-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['archive'], ['class' => 'b-btn b-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
