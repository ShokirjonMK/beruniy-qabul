<?php
use common\models\EduForm;
use common\models\Direction;
use common\models\Languages;
use yii\helpers\Url;
use common\models\EduDirection;
use common\models\Lang;
use common\models\EduType;
use common\models\Branch;

$eduTypes = EduType::find()
    ->where([
            'status' => 1,
        'is_deleted' => 0
    ])
    ->all();

$branchs = Branch::find()
    ->where([
        'status' => 1,
        'is_deleted' => 0
    ])
    ->all();

$eduForms = EduForm::find()
    ->where([
        'status' => 1,
        'is_deleted' => 0
    ])
    ->all();

$langs = Lang::find()
    ->where([
        'status' => 1,
        'is_deleted' => 0
    ])
    ->all();
$lang = Yii::$app->language;
?>



<div class="ik_content" id="ik_direc">
    <div class="ik_content_box">
        <div class="ik_content_direction">
            <div class="root-item">
                <div class="ik_main_title">
                    <p><?= Yii::t("app" , "a12") ?></p>
                    <h4><?= Yii::t("app" , "a13") ?></h4>
                </div>

                <?php if (count($eduTypes) > 0) : ?>
                    <div class="ik_nav_pills">
                        <div class="ik_nav_pills_item">
                            <ul class="nav nav-pills mb-4 view-tabs" id="pills-tab" role="tablist">
                                <?php $a = 1 ?>
                                <?php foreach ($eduTypes as $eduType) : ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php if ($a == 1) { echo "active";} ?>" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills_ik<?= $a ?>" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                                            <?= $eduType['name_'.$lang] ?>
                                        </button>
                                    </li>
                                    <?php $a++; ?>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <?php $a = 1 ?>
                                <?php foreach ($eduTypes as $eduType) : ?>
                                    <div class="tab-pane fade <?php if ($a == 1) { echo "show active";} ?>" id="pills_ik<?= $a ?>" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                        <div class="grid-view">
                                            <div class="ika_directions">
                                                <?php foreach ($branchs as $branch) : ?>
                                                    <?php
                                                        $directions = Direction::find()
                                                            ->where([
                                                                'branch_id' => $branch->id,
                                                                'status' => 1,
                                                                'is_deleted' => 0
                                                            ])
                                                            ->all();
                                                    ?>
                                                    <h5 class="ika_directions_title"><?= $branch['name_'.$lang] ?></h5>
                                                    <div class="row">
                                                        <?php foreach ($directions as $direction) : ?>
                                                            <?php
                                                            $eduDirections = EduDirection::find()
                                                                ->where(['status' => 1, 'is_deleted' => 0])
                                                                ->andWhere(['direction_id' => $direction->id, 'edu_type_id' => $eduType->id])
                                                                ->all();
                                                            ?>
                                                            <?php if (count($eduDirections) > 0) : ?>
                                                                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                                                    <div class="ika_direction_item">
                                                                        <h6><?= $direction->code." - ".$direction['name_'.$lang] ?></h6>
                                                                        <div class="ika_direction_type">
                                                                            <?php foreach ($eduForms as $eduForm) : ?>
                                                                                <?php
                                                                                $t = true;
                                                                                $langQuerys = EduDirection::find()
                                                                                    ->where([
                                                                                        'edu_form_id' => $eduForm->id,
                                                                                        'direction_id' => $direction->id,
                                                                                        'edu_type_id' => $eduType->id,
                                                                                        'status' => 1,
                                                                                        'is_deleted' => 0
                                                                                    ])
                                                                                    ->all();
                                                                                ?>
                                                                                <?php if (count($langQuerys) > 0) : ?>
                                                                                    <div class="ika_direction_type_item">
                                                                                        <?php foreach ($langQuerys as $langQuery) : ?>
                                                                                            <?php if ($t) : ?>
                                                                                                <?php $t = false; ?>
                                                                                                <div class="ika_direction_type_item_text">
                                                                                                    <p><?= $eduForm['name_'.$lang] ?></p>
                                                                                                    <p><?= $langQuery->duration ?>-yil</p>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                            <div class="ika_direction_lang">
                                                                                                <p><?= $langQuery->lang['name_'.$lang] ?></p>
                                                                                                <p><?= number_format((int)$langQuery->price, 0, '', ' '); ?></p>
                                                                                            </div>
                                                                                        <?php endforeach; ?>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $a++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

