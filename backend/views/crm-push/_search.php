<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CrmPushSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="crm-push-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-4">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'student_id') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'first_name') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'last_name') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'middle_name') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'type') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'lead_id') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'status') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end gap-2 mb-3">
        <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'b-btn b-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), ['index'], ['class' => 'b-btn b-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
