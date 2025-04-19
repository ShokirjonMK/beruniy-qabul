<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Branch;
use common\models\Status;

/** @var yii\web\View $this */
/** @var common\models\IkIp $model */
/** @var yii\widgets\ActiveForm $form */
$branchs = Branch::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="ik-ip-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-lg-4 col-md-6 col-12'>
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
        <div class='col-lg-4 col-md-6 col-12'>
            <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-lg-4 col-md-6 col-12'>
            <div class="form-group">
                <?= $form->field($model, 'status')->dropDownList(
                    Status::accessStatus(),
                    ['class'=>'form-select form-control']) ?>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
