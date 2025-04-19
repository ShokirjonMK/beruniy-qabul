<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

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
        <div class="login_left_right">
            <div class="login_box">
                <div class="login_right">

                    <div class="login_top">
                        <div class="login_top_left">
                            <a href="#">
                                <img src="/frontend/web/images/logo_blue.svg" alt="">
                            </a>
                        </div>
                        <div class="login_top_right">

                            <div class="translation cab_flag">
                                <div class="dropdown">

                                    <button class="dropdown-toggle link-hover" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                        <p>Uzbek</p> <img src="/frontend/web/images/uzb.png" alt="">
                                    </button>

                                    <ul class="dropdown-menu" >
                                        <ul class="drop_m_ul">
                                            <li>
                                                <a href="/admin/supper-admin/view?id=1">
                                                    <span>Russian</span>
                                                    <img src="/frontend/web/images/uzb.png" alt="">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <span>English</span>
                                                    <img src="/frontend/web/images/uzb.png" alt="">
                                                </a>
                                            </li>

                                        </ul>
                                    </ul>

                                </div>
                            </div>


                        </div>
                    </div>

                    <?= $content ?>
                </div>
                <div class="login_left">
                    <img src="/frontend/web/images/logo_b2.svg" alt="" class="login_logo_img">
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
