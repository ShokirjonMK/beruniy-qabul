<?php
use common\models\{Student, Exam, Status, StudentOferta};
use yii\helpers\{Url, Html};

/** @var $model */
/** @var Student $student */
/** @var $id */

$lang = Yii::$app->language;
$exam = Exam::findOne(['student_id' => $student->id, 'direction_id' => $student->direction_id, 'is_deleted' => 0]);
$oferta = StudentOferta::findOne(['student_id' => $student->id, 'edu_direction_id' => $student->edu_direction_id, 'status' => 1, 'is_deleted' => 0]);

if ($exam):
    $eduDirection = $exam->eduDirection;
    $direction = $eduDirection->direction;
    ?>

    <div class="qabul">
        <div class="down_box">
            <div class="down_title">
                <h6><i class="fa-brands fa-slack"></i> &nbsp;&nbsp; <?= $direction->code . " - " . $direction['name_' . $lang] ?></h6>
            </div>
            <div class="down_content">
                <?php
                $items = [
                    ['a80', $eduDirection->eduType['name_' . $lang]],
                    ['a59', $eduDirection->lang['name_' . $lang]],
                    ['a60', $eduDirection->eduForm['name_' . $lang]],
                    ['a64', Status::getExamStatus($student->exam_type)]
                ];
                foreach ($items as $item): ?>
                    <div class="down_content_box">
                        <div class="down_content_box_left">
                            <i class="fa-regular fa-bookmark"></i>
                        </div>
                        <div class="down_content_box_right">
                            <p><?= Yii::t("app", $item[0]) ?></p>
                            <h6><?= $item[1] ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($eduDirection->is_oferta && $oferta): ?>
            <div class="down_box top30">
                <div class="down_title">
                    <h6><i class="fa-solid fa-check-to-slot"></i> &nbsp;&nbsp; <?= Yii::t("app", "a103") ?> </h6>
                </div>
                <div class="down_content">
                    <div class="cfile_box">
                        <?php
                        $statuses = [
                            0 => ['danger', 'a82'],
                            1 => ['', 'a83'],
                            2 => ['active', 'a84'],
                            3 => ['danger', 'a85']
                        ];
                        if (isset($statuses[$oferta->file_status])): ?>
                            <div class="cfile_box_head_right <?= $statuses[$oferta->file_status][0] ?>">
                                <p><?= Yii::t("app", $statuses[$oferta->file_status][1]) ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="cfile_box_head">
                            <div class="cfile_box_head_left">
                                <h5> <span></span> <?= Yii::t("app", "a91") ?></h5>
                            </div>
                        </div>

                        <?php if ($oferta->file_status == 0): ?>
                            <div class="cfile_box_content_question">
                                <p><span><i class="fa-solid fa-question"></i></span> <?= Yii::t("app", "a92") ?></p>
                            </div>
                            <div class="cfile_box_content_upload">
                                <?= Html::a(Yii::t("app", "a93"), Url::to(['file/create-oferta', 'id' => $oferta->id]), [
                                    "data-bs-toggle" => "modal",
                                    "data-bs-target" => "#studentOfertaCreate",
                                ]) ?>
                            </div>
                        <?php else: ?>
                            <div class="cfile_box_content">
                                <div class="cfile_box_content_file">
                                    <div class="cfile_box_content_file_left">
                                        <a href="/frontend/web/uploads/<?= $student->id ?>/<?= $oferta->file ?>" target="_blank">
                                            <span><i class="fa-solid fa-file-export"></i></span><?= Yii::t("app", "a89") ?>
                                        </a>
                                    </div>
                                    <?php if ($oferta->file_status != 2): ?>
                                        <div class="cfile_box_content_file_right">
                                            <?= Html::a('<i class="fa-solid fa-trash"></i>', Url::to(['file/del-oferta', 'id' => $oferta->id]), [
                                                'title' => Yii::t('app', 'a94'),
                                                'class' => "sertificat_box_trash",
                                                'data-bs-toggle' => "modal",
                                                'data-bs-target' => "#studentSerOfertaDelete",
                                            ]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $modals = ["studentSerDelete", "studentSerOfertaDelete", "studentSerCreate", "studentOfertaCreate"];
    foreach ($modals as $modal): ?>
        <div class="modal fade" id="<?= $modal ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="ikmodel">
                        <div class="ikmodel_item">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="<?= $modal ?>Body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php
    $js = <<<JS
$(document).ready(function() {
    function getModalScript(id) {
        $('#'+id).on('show.bs.modal', function (e) {
            $(this).find('#'+id+'Body').load($(e.relatedTarget).attr('href'));
        });
    }
    ['studentSerDelete', 'studentSerCreate', 'studentOfertaCreate', 'studentSerOfertaDelete'].forEach(getModalScript);
});
JS;
    $this->registerJs($js);
endif; ?>
