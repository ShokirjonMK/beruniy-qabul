<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use yii\helpers\ArrayHelper;
use common\models\Languages;
use common\models\Lang;

/** @var yii\web\View $this */
/** @var common\models\Subjects $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="page">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                        <?php $items = ArrayHelper::map(Lang::find()->all(), 'id', 'name_uz'); ?>
                        <?= $form->field($model, 'language_id')->dropDownList(
                            $items,
                            ['prompt'=>'Fan tilini tanlang!']
                        ); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'status')->dropDownList(
                            Status::accessStatus(),
                            ['class'=>'form-select form-control']) ?>
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
