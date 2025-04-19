<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Consulting;

/** @var yii\web\View $this */
/** @var common\models\Branch $model */
/** @var yii\widgets\ActiveForm $form */
$cons = Consulting::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class='form-group'>
                <?= $form->field($model, 'cons_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($cons, 'id', 'name'),
                    'options' => ['placeholder' => 'Hamkor tanlang'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Hamkor tanlang'); ?>
            </div>
            <div class='form-group'>
                <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='form-group'>
                <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='form-group'>
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'telegram')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'instagram')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>
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
        </div>
        <div class="col-12 col-md-6">
            <div class='form-group'>
                <?= $form->field($model, 'address_uz')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'address_ru')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'address_en')->textInput(['maxlength' => true]) ?>
            </div>

            <div class='form-group'>
                <?= $form->field($model, 'location')->textarea(['rows' => 6]) ?>
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

            <div class='form-group'>
                <?= $form->field($model, 'rector_uz')->textInput(['maxlength' => true]) ?>
            </div>

        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
