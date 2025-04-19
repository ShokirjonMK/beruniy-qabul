<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">

        <?= $this->render('_meta');?>
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?= $this->render('_css'); ?>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="root">

        <?= $content ?>

    </div>

    <?= $this->render('_script');?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
