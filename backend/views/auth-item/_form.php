<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AuthItem;
use common\models\AuthItemChild;
use common\models\AuthChild;
use common\models\Status;
use common\models\Branch;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\AuthItem $model */
/** @var common\models\AuthItem [] $authItems */
/** @var yii\widgets\ActiveForm $form */
$authItems = AuthItem::find()
    ->where(['not in' , 'name' , 'super_admin'])
    ->all();

$branchs = Branch::find()
    ->where(['is_deleted' => 0 , 'status' => 1])->all();
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="form-group">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'type')->dropDownList(
                            Status::rolType(),
                            ['class'=>'form-select form-control']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($branchs, 'id', 'name_uz'),
                            'options' => ['placeholder' => 'Filial tanlang'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Filial tanlang'); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'status')->dropDownList(
                            Status::accessStatus(),
                            ['class'=>'form-select form-control']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="view-info-right">
                                    <h6>Bo'ysinuvchi</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="view-info-right">
                                    <h6>Bo'ysindiruvchi</h6>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($authItems as $authItem) : ?>
                            <?php
                            $ach = false;
                            $ap = false;
                            $authChild = AuthChild::findOne([
                                'parent' => $model->name,
                                'child' => $authItem->name,
                            ]);
                            if ($authChild) {
                                $ap = true;
                            }
                            $authParent = AuthChild::findOne([
                                'child' => $model->name,
                                'parent' => $authItem->name,
                            ]);
                            if ($authParent) {
                                $ach = true;
                            }
                            ?>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="subject_box">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" class="bu-check" name="rol[<?= $authItem->name ?>]" id="check_<?= $authItem->name ?>" value="1" <?php if ($ach) { echo "checked";} ?>>
                                            <label for="check_<?= $authItem->name ?>" class="permission_label"><?= $authItem->name ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="subject_box">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" class="bu-check" name="rol[<?= $authItem->name ?>]" id="check2_<?= $authItem->name ?>" value="2" <?php if ($ap) { echo "checked";} ?>>
                                            <label for="check2_<?= $authItem->name ?>" class="permission_label"><?= $authItem->name ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-2">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
