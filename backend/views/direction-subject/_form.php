<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Subjects;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Status;

/** @var yii\web\View $this */
/** @var common\models\DirectionSubject $model */
/** @var common\models\EduDirection $eduDirection */
/** @var yii\widgets\ActiveForm $form */
$subjects = Subjects::find()
    ->where(['status' => 1, 'is_deleted' => 0 , 'language_id' => $eduDirection->lang_id])
    ->all();
?>

<div class="direction-subject-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-12 col-md-6'>
            <?= $form->field($model, 'subject_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($subjects, 'id', 'name_uz'),
                'options' => ['placeholder' => 'Fan tanlang...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Fan tanlang...'); ?>
        </div>
        <div class='col-12 col-md-6'>
            <?= $form->field($model, 'ball')->textInput() ?>
        </div>
        <div class='col-12 col-md-6'>
            <?= $form->field($model, 'count')->textInput(['value' => $model->isNewRecord ? 30 : $model->count]) ?>
        </div>
        <div class='col-12 col-md-6'>
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => Status::accessStatus(),
                // 'options' => ['placeholder' => 'Status tanlang ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-2">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
