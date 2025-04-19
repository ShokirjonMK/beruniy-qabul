<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Branch;

$lang = Yii::$app->language;
$branchs = Branch::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$this->title = Yii::t("app" , "a32");
?>

<div class="login_form">
    <div>
        <div class="login_p">
            <h4><?= Yii::t("app" , "a32") ?></h4>
            <p><?= Yii::t("app" , "a33") ?></p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'top50'],
            'fieldConfig' => [
                'template' => '{input}{label}{error}',
            ]
        ]); ?>

        <div class="form-group">
            <?= $form->field($model, 'filial_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($branchs, 'id', 'name_'.$lang),
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>

        <div class="position-relative">
            <?= $form->field($model, 'username')
                ->widget(\yii\widgets\MaskedInput::class, [
                    'mask' => '+\9\9\8 (99) 999-99-99',
                    'options' => [
                        'placeholder' => '+998 (__) ___-__-__',
                    ],
                ])->label('<i class="fa-solid fa-user-check"></i>') ?>
        </div>

        <?= $form->field($model, 'password' , ['template' => "{input} \n {label} \n {error} \n <i id='eyePassword' class='fas fa-eye-slash'></i>"])->passwordInput(['id'=>'eye_password', 'placeholder' => 'Parol'])->label('<i class="fa-solid fa-lock"></i>') ?>

        <?= $form->field($model, 'reset_password' , ['template' => "{input} \n {label} \n {error} \n <i id='eyePassword1' class='fas fa-eye-slash'></i>"])->passwordInput(['id'=>'eye_password1', 'placeholder' => 'Parolni takrorlang'])->label('<i class="fa-solid fa-lock"></i>') ?>

        <div class="sign_in">
            <?= Html::submitButton(Yii::t("app" , "a31"), ['class' => 'b-btn b-primary btn-block mtop30', 'name' => 'login-button']) ?>
        </div>

        <div class="sign_up">
            <p><?= Yii::t("app" , "a34") ?> &nbsp; <a href="<?= Url::to(['site/login']) ?>"><?= Yii::t("app" , "a23") ?></a></p>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
