<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Languages $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <?php $form = ActiveForm::begin(); ?>
        <div class="form-section">
            <div class="form-section_item">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group d-flex justify-content-end mt-4 mb-5">
            <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
