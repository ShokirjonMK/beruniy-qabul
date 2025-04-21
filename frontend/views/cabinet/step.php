<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

/** @var $model */
/** @var \common\models\Student $student */
/** @var \common\models\Student $user */
/** @var $id */
?>

<div class="step_bar">
    <div class="step_bar_abs">
        <div class="step_bar_abs_animate"></div>
    </div>
    <div class="step_bar_ul">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <li class="<?php if ($i == $id) { echo "active";}  ?>">
                <a href="<?php if ($i <= $user->step) { echo Url::to(['cabinet/step' , 'id' => $i]);} else { echo "javascript: void(0);";}  ?> ">
                    <?php if ($i < $user->step) : ?>
                        <i class="fa-solid fa-check"></i>
                    <?php else: ?>
                        <?= $i ?>
                    <?php endif; ?>
                </a>
            </li>
        <?php endfor; ?>
    </div>
</div>

<div class="step_title">
    <p><?= $id ?> - <?= Yii::t("app" , "a138") ?></p>
    <?php if ($id == 1) :  ?>
        <h6><?= Yii::t("app" , "a139") ?></h6>
    <?php elseif ($id == 2) :  ?>
        <h6><?= Yii::t("app" , "a140") ?></h6>
    <?php elseif ($id == 3) :  ?>
        <h6><?= Yii::t("app" , "a3") ?></h6>
    <?php elseif ($id == 4) :  ?>
        <h6><?= Yii::t("app" , "a37") ?></h6>
    <?php else: ?>
        <h6>-----------</h6>
    <?php endif; ?>
</div>


<?php if ($id == 1) : ?>
    <?php if (Yii::$app->params['ikIntegration'] == 1): ?>
        <?= $this->render('_form-step1' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php endif; ?>
    <?php if (Yii::$app->params['ikIntegration'] == 2): ?>
        <?= $this->render('_form-step12' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php endif; ?>
    <?php if (Yii::$app->params['ikIntegration'] == 3): ?>
        <?= $this->render('_form-step13' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php endif; ?>

<?php elseif ($id == 2): ?>
    <?= $this->render('_form-step2' , [
        'model' => $model,
        'student' => $student
    ]) ?>
<?php elseif ($id == 3): ?>
    <?php if ($student->edu_type_id == 1) :  ?>
        <?= $this->render('_form-step3' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php elseif ($student->edu_type_id == 2) :  ?>
            <?= $this->render('_form-step32' , [
                'model' => $model,
                'student' => $student
            ]) ?>
    <?php elseif ($student->edu_type_id == 3) :  ?>
        <?= $this->render('_form-step33' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php elseif ($student->edu_type_id == 4) :  ?>
        <?= $this->render('_form-step34' , [
            'model' => $model,
            'student' => $student
        ]) ?>
    <?php endif;  ?>
<?php elseif ($id == 4): ?>
    <?= $this->render('_form-step4' , [
        'model' => $model,
        'student' => $student
    ]) ?>
<?php endif; ?>