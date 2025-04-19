<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use common\models\Branch;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\ExamDate $model */
/** @var yii\widgets\ActiveForm $form */

$branchs = Branch::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="exam-date-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-6 col-12'>
            <div class="form-group">
                <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
                    'options' => ['placeholder' => 'Filial tanlang...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        </div>
        <div class='col-md-6 col-12'>
            <div class="form-group">
                <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Sanani va vaqtni tanlang...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                         'format' => 'yyyy-mm-dd hh:ii',
                        'todayHighlight' => true,
                        'convertFormat' => true,
                        'todayBtn' => true,
                        'showMeridian' => false,
                        'minuteStep' => 60
                    ]
                ]) ?>
            </div>
        </div>
        <div class='col-md-6 col-12'>
            <div class="form-group">
                <?= $form->field($model, 'limit')->textInput() ?>
            </div>
        </div>
        <div class='col-md-6 col-12'>
            <div class="form-group">
                <?= $form->field($model, 'status')->dropDownList(
                    Status::accessStatus(),
                    ['class'=>'form-select form-control']) ?>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-3 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
