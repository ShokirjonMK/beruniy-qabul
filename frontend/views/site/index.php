<?php

use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'ABU RAYHON BERUNIY UNIVERSITETI';
?>

<div class="mainPage">
    <div class="ban_content">
        <div class="banner">
            <div class="banner-center" data-aos="fade-up" data-aos-duration="3000">
                <!--                <h3>GLOBAL</h3>-->
                <h1>ABU RAYHON </h1>
                <h3>BERUNIY UNIVERSITETI</h3>
                <h3><?= Yii::t("app", "a11") ?></h3>
                <div class="banner-link">
                    <a style="font-size: 52px;" href="<?= Url::to(['site/sign-up']) ?>">
                        <?= Yii::t("app", "a4") ?>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height=".9rem" fill="none" viewBox="0 0 17 12">
                                <path stroke="currentColor" stroke-miterlimit="10" d="M8.647 11.847S10.007 7.23 16 6.336M8.645.805S10.005 5.423 16 6.317M0 6.27h15.484"></path>
                            </svg>
                            <svg width="20px" xmlns="http://www.w3.org/2000/svg" height=".9rem" fill="none" viewBox="0 0 17 12">
                                <path stroke="currentColor" stroke-miterlimit="10" d="M8.647 11.847S10.007 7.23 16 6.336M8.645.805S10.005 5.423 16 6.317M0 6.27h15.484"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="banner-logo">
                <div class="circle">
                    <div class="logo">
                        <img src="/frontend/web/images/beruniy_logo_oq.png" alt="">
                    </div>
                    <div class="circle-text">
                        <p>ABU * RAYHON * BERUNIY * UNIVERSITETI *</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->render('_content'); ?>