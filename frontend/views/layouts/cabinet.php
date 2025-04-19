<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\models\Student;
use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use common\widgets\Alert;

AppAsset::register($this);
$user = Yii::$app->user->identity;
$student = Student::findOne(['user_id' => $user->id]);
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
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link href="/frontend/web/images/logo_blue.svg" rel="icon">
        <link href="/frontend/web/images/logo_blue.svg" rel="apple-touch-icon">
        <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('loading') ; ?>

    <div class="root">
        <?= $this->render('_header') ; ?>

        <div class="root-item">
            <div class="ika_content">
                <div class="ika_content_left">
                    <?= $this->render('_cab_sidebar' , [
                        'student' => $student
                    ]) ; ?>
                </div>
                <div class="ika_content_right">
                    <?= $content ?>
                </div>
            </div>
        </div>

        <?= $this->render('_footer') ; ?>
    </div>

    <?php $this->endBody() ?>
    <?= Alert::widget() ?>
    </body>
    </html>
<?php $this->endPage();
