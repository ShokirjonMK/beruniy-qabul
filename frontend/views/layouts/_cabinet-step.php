<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\Languages;

$languages = Languages::find()->where(['is_deleted' => 0, 'status' => 1])->all();
$lang = Yii::$app->language;
$langId = 1;
if ($lang == 'ru') {
    $langId = 3;
} elseif ($lang == 'en') {
    $langId = 2;
}

AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $url = Url::to(['cabinet/index']);
} else {
    $url = Url::to(['/']);
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
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('loading') ; ?>

    <div class="root">
        <div class="cstep">
            <div class="cstep_box">
                <div class="cstep_box_left order-2">
                    <div class="cstep_head">
                        <div class="cstep_head_box">
                            <div class="cstep_head_left">
                                <a href="<?= $url ?>">
                                    <img src="/frontend/web/images/new_logo.png" alt="">
                                </a>
                            </div>
                            <div class="cstep_head_right">
                                <div class="translation cab_flag">
                                    <div class="dropdown">

                                        <button class="dropdown-toggle link-hover" style="background: none;" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                            <?php foreach ($languages as $language): ?>
                                                <?php if ($language->id == $langId): ?>
                                                    <p><?= $language['name_'.$lang] ?></p>
                                                    <?php if ($language->id == 1): ?>
                                                        <img src="/frontend/web/images/uzb.png" alt="">
                                                    <?php elseif ($language->id == 2) : ?>
                                                        <img src="/frontend/web/images/eng1.png" alt="">
                                                    <?php elseif ($language->id == 3) : ?>
                                                        <img src="/frontend/web/images/rus.png" alt="">
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <ul class="drop_m_ul">
                                                <?php foreach ($languages as $language): ?>
                                                    <?php if ($language->id != $langId): ?>
                                                        <li>
                                                            <a href="<?= Url::to(['site/lang' , 'id' => $language->id]) ?>">
                                                                <span><?= $language['name_'.$lang] ?></span>
                                                                <?php if ($language->id == 1): ?>
                                                                    <img src="/frontend/web/images/uzb.png" alt="">
                                                                <?php elseif ($language->id == 2) : ?>
                                                                    <img src="/frontend/web/images/eng1.png" alt="">
                                                                <?php elseif ($language->id == 3) : ?>
                                                                    <img src="/frontend/web/images/rus.png" alt="">
                                                                <?php endif; ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </ul>

                                    </div>
                                </div>

                                <?php if (!Yii::$app->user->isGuest) : ?>
                                    <div class="cab_drop translation">
                                        <div class="dropdown">
                                            <button class="dropdown-toggle link-hover" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="cap_profile_logo">
                                                    <img src="/frontend/web/images/man.svg">
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <ul class="drop_m_ul">
                                                    <li>
                                                        <?php $text = '<svg fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M12.75 17.5a.75.75 0 0 0 0-1.5H6.5a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6.25a.75.75 0 0 0 0-1.5H6.5A3.5 3.5 0 0 0 3 6v8a3.5 3.5 0 0 0 3.5 3.5h6.25Zm1-11.3a.75.75 0 0 1 1.05.04l3 3.25c.27.29.27.73 0 1.02l-3 3.25a.75.75 0 1 1-1.1-1.02l1.84-1.99H7.75a.75.75 0 0 1 0-1.5h7.79l-1.84-2a.75.75 0 0 1 .04-1.05Z" fill="currentColor"></path></svg>
                                                            &nbsp;
                                                            '.Yii::t("app" , "a41");  ?>
                                                        <?= Html::a(Yii::t('app', $text), ['site/logout'], [
                                                            'class' => 'dropdown-item',
                                                            'data' => [
                                                                'method' => 'post',
                                                            ],
                                                        ]) ?>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="cstep_content">
                        <?= $content ?>
                    </div>
                </div>
                <div class="cstep_box_right order-1">
                    <img src="/frontend/web/images/logo_b2.svg" alt="">
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
    <?= Alert::widget() ?>
    </body>
    </html>
<?php $this->endPage();
