<?php
use yii\helpers\Url;
use common\models\Student;
use yii\bootstrap5\Html;
use common\models\Languages;

$languages = Languages::find()->where(['is_deleted' => 0, 'status' => 1])->all();
$lang = Yii::$app->language;
$langId = 1;
if ($lang == 'ru') {
    $langId = 3;
} elseif ($lang == 'en') {
    $langId = 2;
}

/** @var Student $student */
/** @var  $class */

$class = 'cab_sidebar';
?>
<div class="cab_head">
        <div class="cab_head_left">
            <a href="<?= Url::to(['cabinet/index']) ?>">
                <img src="/frontend/web/images/new_logo.png" alt="">
            </a>
        </div>
        <div class="cab_head_right">

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

            <div class="cab_drop translation cab_user">
                <div class="dropdown">
                    <button class="dropdown-toggle link-hover" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="cap_profile_logo">
                            <img src="/frontend/web/images/man.svg">
                        </span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <ul class="drop_m_ul">
                            <li>
                                <a class="dropdown-item" href="<?= Url::to(['cabinet/index']) ?>">
                                    <svg fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a4 4 0 1 0 0 8 4 4 0 0 0 0-8ZM6 6a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-2 5a2 2 0 0 0-2 2c0 1.7.83 2.97 2.13 3.8A9.14 9.14 0 0 0 9 18c.41 0 .82-.02 1.21-.06A5.5 5.5 0 0 1 9.6 17 12 12 0 0 1 9 17a8.16 8.16 0 0 1-4.33-1.05A3.36 3.36 0 0 1 3 13a1 1 0 0 1 1-1h5.6c.18-.36.4-.7.66-1H4Zm6.64 2.92a2 2 0 0 0 1.43-2.48l-.16-.55c.25-.2.53-.37.82-.5l.34.36a2 2 0 0 0 2.9 0l.33-.35c.3.14.58.32.84.52l-.13.42a2 2 0 0 0 1.46 2.52l.35.09a4.7 4.7 0 0 1 0 1.01l-.45.12a2 2 0 0 0-1.43 2.48l.15.55c-.25.2-.53.37-.82.5l-.34-.36a2 2 0 0 0-2.9 0l-.33.35c-.3-.14-.58-.32-.84-.52l.13-.42a2 2 0 0 0-1.46-2.52l-.35-.09a4.71 4.71 0 0 1 0-1.01l.46-.12Zm4.86.58a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z" fill="currentColor"></path>
                                    </svg>
                                    &nbsp;
                                    <?= Yii::t("app" , "a40") ?>
                                </a>
                            </li>
                            <li>
                                <?= Html::a('<svg fill="currentColor" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M12.75 17.5a.75.75 0 0 0 0-1.5H6.5a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6.25a.75.75 0 0 0 0-1.5H6.5A3.5 3.5 0 0 0 3 6v8a3.5 3.5 0 0 0 3.5 3.5h6.25Zm1-11.3a.75.75 0 0 1 1.05.04l3 3.25c.27.29.27.73 0 1.02l-3 3.25a.75.75 0 1 1-1.1-1.02l1.84-1.99H7.75a.75.75 0 0 1 0-1.5h7.79l-1.84-2a.75.75 0 0 1 .04-1.05Z" fill="currentColor"></path></svg>
                                    &nbsp;
                                    '.Yii::t("app" , "a41"), ['site/logout'], [
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

            <button class="cab_offcans" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="off_head">
        <h6 class="off-title" id="offcanvasExampleLabel"><?= Yii::t("app" , "a42") ?></h6>
        <button type="button" class="btn-close off_close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>

        <div class="off_info">
            <div class="off_info_left">
                <span class="cap_profile_logo" style="background: #fff;">
                    <img src="/frontend/web/images/man.svg">
                </span>
            </div>
            <div class="off_info_right">
                <h6><?= $student->last_name.' '.$student->first_name ?></h6>
                <p>ID: <?= $student->id ?></p>
            </div>
        </div>

    </div>
    <div class="offcanvas-body">

        <div class="off_body">
            <?= $this->render('_cabinet-sidebar' , [
                'student' => $student,
                'class' => $class
            ]) ; ?>
        </div>

    </div>
</div>