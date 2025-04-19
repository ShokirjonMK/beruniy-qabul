<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ConsultingSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'hr') ?>

    <?= $form->field($model, 'bank_name_uz') ?>

    <?= $form->field($model, 'bank_name_ru') ?>

    <?php // echo $form->field($model, 'bank_name_en') ?>

    <?php // echo $form->field($model, 'bank_adress_uz') ?>

    <?php // echo $form->field($model, 'bank_adress_ru') ?>

    <?php // echo $form->field($model, 'bank_adress_en') ?>

    <?php // echo $form->field($model, 'mfo') ?>

    <?php // echo $form->field($model, 'inn') ?>

    <?php // echo $form->field($model, 'tel1') ?>

    <?php // echo $form->field($model, 'tel2') ?>

    <?php // echo $form->field($model, 'domen') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
