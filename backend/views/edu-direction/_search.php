<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Direction;
use common\models\EduType;
use common\models\EduForm;
use common\models\Lang;
use common\models\Branch;


/** @var yii\web\View $this */
/** @var common\models\EduDirectionSearch $model */
/** @var yii\widgets\ActiveForm $form */
$directions = [];
if ($model->branch_id != null) {
    $directions = Direction::find()
        ->where(['is_deleted' => 0, 'branch_id' => $model->branch_id, 'status' => 1])->all();
    $directions = ArrayHelper::map($directions, 'id', 'name');
}
$langs = Lang::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduTypes = EduType::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduForms = EduForm::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="edu-direction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-3">
        <div class="form-section_item">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
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
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <div class="form-group">
                            <?= $form->field($model, 'direction_id')->widget(Select2::classname(), [
                                'data' => $directions,
                                'options' => ['placeholder' => 'Yo\'nalishlar'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Yo\'nalishlar'); ?>
                        </div>
                    </div>
                </div>

                <div class='col-12 col-md-6 col-lg-3'>
                    <div class="form-group">
                        <?= $form->field($model, 'edu_type_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($eduTypes, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim turi'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim turi'); ?>
                    </div>
                </div>
                <div class='col-12 col-md-6 col-lg-3'>
                    <div class="form-group">
                        <?= $form->field($model, 'edu_form_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($eduForms, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim shakli'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim shakli'); ?>
                    </div>
                </div>
                <div class='col-12 col-md-6 col-lg-3'>
                    <div class="form-group">
                        <?= $form->field($model, 'lang_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($langs, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Ta\'lim tili'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Ta\'lim tili'); ?>
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

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#edudirectionsearch-branch_id").on('change', function () {
            var branch_id = $(this).val();
            if (branch_id > 0) {
                $.ajax({
                    url: '../file/direction-branch/',
                    data: {branch_id: branch_id},
                    type: 'GET',
                    success: function (data) {
                        $("#edudirectionsearch-direction_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            } else {
                $("#edudirectionsearch-direction_id").html('');
            }
        });
    });
JS;
$this->registerJs($js);
?>
