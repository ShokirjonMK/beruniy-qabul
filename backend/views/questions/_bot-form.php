<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Status;
use yii\helpers\ArrayHelper;
use common\models\Languages;
use common\models\Options;

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
                        <?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'photo')->fileInput() ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php
                            $variant = false;
                            if ($model->id != null) {
                                $option = Options::findOne([
                                        'is_deleted' => 0,
                                    'question_id' => $model->id,
                                    'order' => $i + 1
                                ]);
                                if ($option) {
                                    $variant = true;
                                }
                            }
                        ?>
                        <div class="form-group">
                            <div class="mb-3 field-questions-text">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="questions-text">
                                        <?php if ($i == 0) : ?>
                                            A variant
                                        <?php elseif ($i == 1): ?>
                                            B variant
                                        <?php elseif ($i == 2): ?>
                                            C variant
                                        <?php elseif ($i == 3): ?>
                                            D variant
                                        <?php endif; ?>
                                    </label>
                                    <input type="radio" class="bu-check" name="check" id="check<?= $i ?>" value="<?= $i ?>" <?php if ($variant) { if ($option->is_correct == 1) { echo "checked"; }} ?>>
                                </div>
                                <textarea id="questions-text" class="form-control" name="variant[<?= $i ?>]"><?php if ($variant) { echo $option->text; } else { echo ".";} ?></textarea>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-5">
        <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
