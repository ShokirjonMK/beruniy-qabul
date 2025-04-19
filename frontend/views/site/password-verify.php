<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */
/** @var \common\models\LoginForm $user */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$time = (date("m/d/Y H:i:s", $user->sms_time));
$phoneNumber = $user->username;
$formattedPhoneNumber = preg_replace('/(?<=\(\d{2}\) )\d{3}-\d{2}-/', '------ ', $phoneNumber);

$this->title = Yii::t("app" , "a35");
?>

<div class="login_form">
    <div>
        <div class="login_p">
            <h4><?= Yii::t("app" , "a35") ?></h4>
            <p><?= Yii::t("app" , "a36") ?> <br> <?= $formattedPhoneNumber ?></p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'top50'],
            'fieldConfig' => [
                'template' => '{input}{label}{error}',
            ]
        ]); ?>

        <div class="form-input">
            <?= $form->field($model, 'sms_code')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '999999',
            ])->label('<span id="minute">--</span> : <span id="secund">--</span>') ?>
        </div>

        <div class="sign_in">
            <?= Html::submitButton(Yii::t("app" , "a37"), ['class' => 'b-btn b-primary b-big btn-block', 'id' => 'sendSmsCode', 'name' => 'login-button']) ?>
            <?= Html::a(Yii::t('app', 'a38'),
                ['send-sms', 'id' => $user->new_key], [
                    'class' => 'b-btn b-primary b-big btn-block verifty_send_sms',
                    'id' => 'sendSMS'
                ]) ?>
        </div>

        <div class="sign_up">
            <p><a href="<?= Url::to(['/']) ?>"><?= Yii::t("app" , "a39") ?></a></p>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>



<?php
$js = <<<JS
(function () {
    const   second = 1000,
            minute = second * 60,
            hour = minute * 60;

    const countDown = new Date(' $time ').getTime();
    const x = setInterval(function () {
        const now = new Date().getTime();
        const distance = countDown - now;
        
        const m = Math.floor((distance % hour) / minute);
        const s = Math.floor((distance % minute) / second);
 
        document.getElementById("minute").innerText = formatTime(m);
        document.getElementById("secund").innerText = formatTime(s);
        
        if (m < 0 && s < 0) {
            clearInterval(x);
            document.getElementById("sendSmsCode").style.display = "none";
            document.getElementById("sendSMS").style.display = "block";
            return;
        }
    }, 1000);

    // Function to format time with leading zero
    function formatTime(time) {
        return time >= 0 ? (time < 10 ? "0" + time : time) : "--";
    }
}());
JS;
$this->registerJs($js);
?>
