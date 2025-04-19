<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use common\models\Course;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\DirectionCourse $model */
/** @var yii\widgets\ActiveForm $form */
$courses = Course::find()
    ->where(['status' => 1, 'is_deleted' => 0])
    ->all();
?>

<div class="direction-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-6'>
            <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($courses, 'id', 'name_uz'),
                'options' => ['placeholder' => 'Kurs tanlang...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Kurs tanlang...'); ?>
        </div>
        <div class='col-6'>
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => Status::accessStatus(),
                'options' => ['placeholder' => 'Status tanlang ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
