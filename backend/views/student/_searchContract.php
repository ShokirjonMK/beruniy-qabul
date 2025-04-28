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

$languages = Lang::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduForm = EduForm::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduType = EduType::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$direction = Direction::find()->where(['is_deleted' => 0])->all();

$branchs = Branch::find()
    ->where(['in', 'id', getBranchOneIk()])
    ->all();

$cons = Consulting::find()
    ->where(['in', 'id', getConsOneIk()])
    ->all();

?>

<div class="student-perevot-search">
    <?php $form = ActiveForm::begin([
        'action' => ['contract'],
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
                        <?= $form->field($model, 'edu_type_id')->widget(Select2::classname(), [
                            'data' =>  ArrayHelper::map($eduType, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim turini tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim shakli <span>*</span>');; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'lang_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($languages, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim  tilini tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim tili <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'edu_form_id')->widget(Select2::classname(), [
                            'data' =>  ArrayHelper::map($eduForm, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim shaklini tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim shakli <span>*</span>');; ?>
                    </div>
                </div>


                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Start date ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ])->label('Start Date <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'end_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'End date ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ])->label('End Date <span>*</span>'); ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'is_down')->widget(Select2::classname(), [
                            'data' => Status::contractStatus(),
                            'options' => ['placeholder' => 'Status tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Status <span>*</span>'); ?>
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
                <?= Html::a(Yii::t('app', 'Reset'), ['contract'], ['class' => 'b-btn b-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
