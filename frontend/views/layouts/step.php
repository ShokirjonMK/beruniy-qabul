<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link href="/admin/edu-assets/image/home-image/logo.svg" rel="icon">
        <link href="/admin/edu-assets/image/home-image/logo.svg" rel="apple-touch-icon">
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('loading') ; ?>

    <div class="root">
        <div class="step_left_right">
            <div class="step_left">
                <div class="step_left_item">
                    <div class="step_logo">
                        <a href="#">
                            <img src="/frontend/web/images/logo.svg" alt="">
                        </a>

                        <div class="translation step_trasnlation">
                            <div class="dropdown">

                                <button class="dropdown-toggle step_flag" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                    <p>Uzbek1</p> <img src="/frontend/web/images/uzb.png" alt="">
                                </button>

                                <ul class="dropdown-menu" >
                                    <ul class="drop_m_ul">
                                        <li>
                                            <a href="/admin/supper-admin/view?id=1">
                                                <span>Russian</span>
                                                <img src="/frontend/web/images/rus.png" alt="">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span>English</span>
                                                <img src="/frontend/web/images/eng1.png" alt="">
                                            </a>
                                        </li>

                                    </ul>
                                </ul>

                            </div>
                        </div>

                    </div>
                    <div class="step_body">
                        <div class="step_box check">
                            <div class="sbox">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="stext">
                                <p>1 - qadam</p>
                                <h6>Pasport ma'lumoti</h6>
                            </div>
                        </div>
                        <div class="step_line check"></div>
                        <div class="step_box active">
                            <div class="sbox">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <div class="stext">
                                <p>1 - qadam</p>
                                <h6>Pasport ma'lumoti</h6>
                            </div>
                        </div>

                        <div class="step_line"></div>
                        <div class="step_box">
                            <div class="sbox">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="stext">
                                <p>1 - qadam</p>
                                <h6>Pasport ma'lumoti</h6>
                            </div>
                        </div>

                        <div class="step_line"></div>
                        <div class="step_box">
                            <div class="sbox">
                                <i class="fa-solid fa-x"></i>
                            </div>
                            <div class="stext">
                                <p>1 - qadam</p>
                                <h6>Pasport ma'lumoti</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step_right">

                <?= $content ?>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
    <?= Alert::widget() ?>
    </body>
    </html>
<?php $this->endPage();
