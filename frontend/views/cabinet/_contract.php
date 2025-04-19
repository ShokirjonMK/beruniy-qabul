<?php
use yii\helpers\Url;

?>


<div class="down_box top30">
    <div class="down_title">
        <h6><i class="fa-solid fa-wand-magic-sparkles"></i> &nbsp;&nbsp; <?= Yii::t("app" , "a113") ?></h6>
    </div>
    <div class="down_content">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <a href="<?= Url::to(['cabinet/contract-load' , 'type' => 2]) ?>" class="down_content_box">
                    <div class="down_content_box_left">
                        <i class="fa-solid fa-file-arrow-down"></i>
                    </div>
                    <div class="down_content_box_right">
                        <p><?= Yii::t("app" , "a114") ?></p>
                        <h6><?= Yii::t("app" , "a116") ?></h6>
                    </div>
                </a>
            </div>
<!--            <div class="col-lg-6 col-md-6 col-sm-12">-->
<!--                <a href="--><?php //= Url::to(['file/contract' , 'type' => 3]) ?><!--" class="down_content_box">-->
<!--                    <div class="down_content_box_left">-->
<!--                        <i class="fa-solid fa-file-arrow-down"></i>-->
<!--                    </div>-->
<!--                    <div class="down_content_box_right">-->
<!--                        <p>--><?php //= Yii::t("app" , "a115") ?><!--</p>-->
<!--                        <h6>--><?php //= Yii::t("app" , "a116") ?><!--</h6>-->
<!--                    </div>-->
<!--                </a>-->
<!--            </div>-->
        </div>
    </div>
</div>