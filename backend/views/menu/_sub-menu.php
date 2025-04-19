<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use common\models\AuthItem;
use yii\helpers\ArrayHelper;
use common\models\Actions;
use common\models\Menu;

/** @var yii\web\View $this */
/** @var common\models\Menu $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="form-section">
    <div class="form-section_item">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                    <?= $form->field($model, 'action_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Actions::find()->all(), 'id', 'description'),
                        'options' => ['placeholder' => 'Select a state ...'],
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Menu::find()->where(['status' => 0])->all(), 'id', 'name_uz'),
                        'options' => ['placeholder' => 'Select a state ...'],
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
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
                <div class="form-group">
                    <?= $form->field($model, 'name_kr')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group d-flex justify-content-end mt-4 mb-5">
    <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'b-btn b-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
