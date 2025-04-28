<?php
use yii\helpers\Url;

?>

<div class="ik_footer">
    <div class="root-item">
        <div class="ik_footer_box">
            <div class="mb_head d-flex justify-content-between align-items-center">
                <div class="mb_head_left">
                    <a href="<?= Url::to(['site/index']) ?>">
                        <img src="/frontend/web/images/logo_new.svg" alt="">
                    </a>
                </div>
            </div>

            <div class="mb_content">
                <div class="line_white"></div>
                <div class="line_red"></div>

                <div class="mb_menu_list2">
                    <ul>
                        <li>
                            <a href="https://tgfu.uz/">
                                <span><?= Yii::t("app" , "a22") ?> <b>TOSHKENT GUMANITAR FANLAR UNIVERSITETI</b></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <img src="/frontend/web/images/logo_new.svg" class="mb_vector_img">
            </div>
        </div>
    </div>
</div>