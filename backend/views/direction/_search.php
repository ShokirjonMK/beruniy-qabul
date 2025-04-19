<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Branch;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var common\models\DirectionSearch $model */
/** @var yii\widgets\ActiveForm $form */
$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="direction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-4">
        <div class="form-section_item">

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class='form-group'>
                        <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Filial tanlang'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Filial tanlang'); ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <?= $form->field($model, 'name_uz') ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <?= $form->field($model, 'code') ?>
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
