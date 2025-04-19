<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MenuSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'role_id') ?>

    <?= $form->field($model, 'name_uz') ?>

    <?= $form->field($model, 'name_en') ?>

    <?= $form->field($model, 'name_ru') ?>

    <?php // echo $form->field($model, 'name_kr') ?>

    <?php // echo $form->field($model, 'action_id') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
