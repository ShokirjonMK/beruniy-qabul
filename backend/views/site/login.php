<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Tizimga kirish';
?>
<div class="site-login">
    <div class="login_content">
        <div class="login_main">
            <div class="login_right">
                <img src="/backend/web/edu-assets/image/edu-smart-image/shape.png" alt="" class="login_shape">
                <div class="login_form">
                    <div>
                        <h4 class="login_title">Tizimga kirish</h4>
                        <p class="title_text mt-2">Kirish uchun pastdagi formani to’ldiring</p>

                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form' ,
                            'options' => ['class' => 'mt-4'] ,
                            'fieldConfig' => [
                                'template' => '{input}{label}{error}',
                            ]
                        ]); ?>

                        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Foydalanuvchi nomi'])->label('<i class="fa-solid fa-user-check"></i>') ?>

                        <?= $form->field($model, 'password' , ['template' => "{input} \n {label} \n {error} \n <i id='eyePassword' class='fas fa-eye-slash'></i>"])->passwordInput(['id'=>'eye_password', 'placeholder' => 'Parol'])->label('<i class="fa-solid fa-lock"></i>') ?>

                        <div class="sign_in">
                            <?= Html::submitButton('Kirish', ['class' => 'b-btn b-primary btn-block', 'name' => 'login-button']) ?>
                        </div>

                        <p class="title_text mt-4"><a href="#">Bosh sahifaga qaytish</a></p>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="login_left">
                <img src="/frontend/web/images/logo_b2.svg" alt="" class="login_logo_img">
            </div>
        </div>
    </div>
</div>
