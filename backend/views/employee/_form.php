<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use common\models\AuthItem;
use yii\helpers\ArrayHelper;
use common\models\Constalting;
use common\models\AuthItemChild;
use common\models\AuthChild;

/** @var yii\web\View $this */
/** @var common\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\Constalting $cons */
/** @var common\models\AuthItem $roles */
/** @var common\models\AuthItem $userRole */
$user = Yii::$app->user->identity;
$userRole = $user->user_role;
$roles = AuthItem::find()
    ->where(['in', 'name', AuthChild::find()->select('child')->where(['parent' => $userRole])])
    ->all();
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'phone')
                            ->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '+\9\9\8 (99) 999-99-99',
                                'options' => [
                                    'placeholder' => '+998 (__) ___-__-__',
                                ],
                            ]) ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'gender')->widget(Select2::classname(), [
                            'data' => Status::gender(),
                            'options' => ['placeholder' => 'Jins tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'brithday')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Tug\'ilgan sanasini kiriting ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'role')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($roles, 'name', 'description'),
                            'options' => ['placeholder' => 'Rol tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="form-section">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => Status::userStatus(),
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

    <div class="form-group d-flex justify-content-end mt-4 mb-5">
        <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
