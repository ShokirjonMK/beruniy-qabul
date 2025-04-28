<?php
use yii\helpers\Url;
use common\models\Languages;
use common\models\Student;

$languages = Languages::find()->where(['is_deleted' => 0, 'status' => 1])->all();
$lang = Yii::$app->language;
$langMap = ['uz' => 1, 'en' => 2, 'ru' => 3];
$langId = $langMap[$lang] ?? 1;
$con = Yii::$app->controller->id;

$user = !Yii::$app->user->isGuest ? Yii::$app->user->identity : null;
$student = $user ? Student::findOne(['user_id' => $user->id]) : null;
$isConfirm = false;
if ($student && $user->step = 5) {
    $isConfirm = $student->isConfirm;
}
$flagMap = [
    1 => 'uzb.png',
    2 => 'eng1.png',
    3 => 'rus.png'
];
?>

<div class="head_mobile">
    <div class="root-item">
        <div class="mb_head d-flex justify-content-between align-items-center">
            <div class="mb_head_left">
                <a href="<?= Url::to(['site/index']) ?>">
                    <img src="/frontend/web/images/logo_new.svg" alt="">
                </a>
            </div>
            <div class="mb_head_right">
                <div class="translation cab_flag" style="background: #fff;">
                    <div class="dropdown">
                        <button class="dropdown-toggle link-hover" style="background: none;" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                            <p style="color: var(--main-color);"><?= $languages[$langId - 1]['name_' . $lang] ?? 'Unknown' ?></p>
                            <img src="/frontend/web/images/<?= $flagMap[$langId] ?? 'uzb.png' ?>" alt="">
                        </button>
                        <ul class="dropdown-menu">
                            <ul class="drop_m_ul">
                                <?php foreach ($languages as $language): ?>
                                    <?php if ($language->id !== $langId): ?>
                                        <li>
                                            <a href="<?= Url::to(['site/lang', 'id' => $language->id]) ?>">
                                                <span><?= $language['name_' . $lang] ?></span>
                                                <img src="/frontend/web/images/<?= $flagMap[$language->id] ?? 'uzb.png' ?>" alt="">
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb_content">
            <div class="line_white"></div>
            <div class="line_red"></div>

            <div class="mb_menu_list2">
                <p><?= Yii::t("app", "a1") ?></p>
                <ul>
                    <?php if ($user): ?>
                        <li class="ika_sidebar_menu">
                            <a data-bs-toggle="offcanvas" href="#offcanvasExampleMenu" role="button" aria-controls="offcanvasExampleMenu">
                                <i class="fa-regular fa-chart-bar"></i>
                                <span><?= Yii::t("app", "Menyu") ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#connectionModal">
                            <i class="fa-solid fa-phone"></i>
                            <span><?= Yii::t("app", "a2") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="/site/index#ik_direc">
                            <i class="fa-solid fa-sitemap"></i>
                            <span><?= Yii::t("app", "a3") ?></span>
                        </a>
                    </li>
                    <?php if ($con != "cabinet"): ?>
                        <li>
                            <a href="<?= Url::to([$user ? 'cabinet/index' : 'site/login']) ?>">
                                <i class="fa-solid fa-user"></i>
                                <span><?= Yii::t("app", $user ? "Kabinetga kirish" : "a4") ?></span>
                            </a>
                        </li>
                    <?php else: ?>
                        <?php if ($isConfirm) : ?>
                            <li>
                                <a href="<?= Url::to(['cabinet/download-file']) ?>">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                    <span><?= Yii::t("app",  "Shartnoma") ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="mb_menu_list">
                <p><?= Yii::t("app", "a5") ?></p>
                <ul>
                    <li><a href="https://www.instagram.com/tgfu_uz/?igshid=MzRlODBiNWFlZA%3D%3D"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://t.me/tgfu_uzb"><i class="fa-brands fa-telegram"></i></a></li>
                    <li><a href="https://www.facebook.com/tgfu.uz?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="https://youtube.com/@tgfuofficial?si=teblBgMHa0fG-nus"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
            </div>

            <img src="/frontend/web/images/logo_new.svg" class="mb_vector_img">
        </div>
    </div>
</div>

<div class="modal fade" id="connectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="ikmodel aloqa_model">
                <div class="ikmodel_item">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modalBody">
                        <img src="/frontend/web/images/logo_new.svg" alt="" width="100px">
                        <div class="ik_connection">
                            <h5><?= Yii::t("app", "a6") ?></h5>
                            <ul>
                                <li><p><?= Yii::t("app", "a7") ?></p></li>
                                <li><a href="tel:+998788886666">+998 78 888-66-66</a></li>
                            </ul>

                            <ul>
                                <li><p><?= Yii::t("app", "a9") ?></p></li>
                                <li>
                                    <a href="#">
                                        Toshkent shahri, Yangixayot tumani, Choshtepa MFY, Choshtepa ko’chasi, 381-uy
                                    </a>
                                </li>
                            </ul>

                            <ul>
                                <li><p><?= Yii::t("app", "a9") ?></p></li>
                                <li>
                                    <a href="#">
                                        Qoraqalpogʻiston Respublikasi, Toʻrtkoʻl tumani, Guliston MFY, Beruniy tup koʻchasi 146-uy
                                    </a>
                                </li>
                            </ul>

<!--                            <div class="modal_vector_img">-->
<!--                                <img src="/frontend/web/images/logo-vector.svg">-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($user): ?>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExampleMenu" aria-labelledby="offcanvasExampleLabel">
        <div class="offSidebarItemSecond">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">
                    <img src="/frontend/web/images/logo_blue.svg" alt="" width="90px">
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?= $this->render('_cab_sidebar', ['student' => $student]); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
