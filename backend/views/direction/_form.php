<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Status;
use yii\helpers\ArrayHelper;
use common\models\Branch;


/** @var yii\web\View $this */
/** @var common\models\Direction $model */
/** @var yii\widgets\ActiveForm $form */
$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="direction-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-12 col-md-6'>
            <div class="form-group">
                <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class='col-12 col-md-6'>
            <div class='form-group'>
                <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
                    'options' => ['placeholder' => 'Filial tanlang'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Filial tanlang'); ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            </div>
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

    <div class="form-group d-flex justify-content-end mt-4 mb-2">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
