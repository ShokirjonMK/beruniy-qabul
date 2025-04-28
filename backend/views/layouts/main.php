<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap5\Html;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">

    <?= $this->render('_meta'); ?>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->render('_css'); ?>
    <link href="/frontend/web/images/logo_blue.svg" rel="icon">
    <link href="/frontend/web/images/logo_blue.svg" rel="apple-touch-icon">
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="root">
        <?= $this->render('_sidebar'); ?>
        <div class="root_right">
            <?= $this->render('_header'); ?>
            <div class="content left-260">
                <div class="main-content">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->render('_script'); ?>
    <?php $this->endBody() ?>
    <?= Alert::widget() ?>
</body>

</html>
<?php $this->endPage();
