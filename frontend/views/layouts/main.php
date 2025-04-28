<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

function getActive($cont, $act)
{
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if ($controller == $cont && $action == $act) {
        return "active";
    } else {
        return false;
    }
}

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
    <link href="/frontend/web/images/logo_blue.svg" rel="icon">
    <link href="/frontend/web/images/logo_blue.svg" rel="apple-touch-icon">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('loading') ; ?>

<div class="root">
    <?= $this->render('_header') ; ?>
    <?= $content ?>
    <?= $this->render('_footer') ; ?>
</div>

<?php $this->endBody() ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init();
</script>
</body>
</html>
<?php $this->endPage();
