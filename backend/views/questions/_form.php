<?php

use yii\helpers\Html;
use common\models\Status;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Questions $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="question-form">

    <script type="text/javascript" src="/backend/web/editor/tinymce6/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>
    <script type="text/javascript">
        if (window.location.search !== '') {
            var urlParams = window.location.search;
            if (urlParams[0] == '?') {
                urlParams = urlParams.substr(1, urlParams.length);
                urlParams = urlParams.split('&');
                for (i = 0; i < urlParams.length; i = i + 1) {
                    var paramVariableName = urlParams[i].split('=')[0];
                    if (paramVariableName === 'language') {
                        _wrs_int_langCode = urlParams[i].split('=')[1];
                        break;
                    }
                }
            }
        }
    </script>

    <script type="text/javascript" src="/backend/web/editor/tinymce6/tinymce.min.js"></script>

    <div class="form-section">
        <div class="form-section_item">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'text')->hiddenInput(['id' => 'htmlcode_div'])->label(false) ?>

            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="wrs_content">
                        <div class="wrs_container">
                            <div class="wrs_row">
                                <div class="wrs_col wrs_s12">
                                    <div id="editorContainer">
                                        <div id="toolbarLocation"></div>
                                        <div id="example" class="wrs_div_box" tabindex="0" spellcheck="false" role="textbox" aria-label="Rich Text Editor, example" title="Rich Text Editor, example">
                                            <?= $model->text ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <?= $form->field($model, 'photo')->fileInput() ?>
                    </div>

                    <div class="wrs_row wrs_padding d-flex justify-content-end mt-6">
                        <div class="wrs_col wrs_s12 wrs_center">
                            <?= Html::submitButton(Yii::t('app', 'Saqlash'),
                                [
                                    'class' => 'b-btn b-primary',
                                    'id' => "previewButton",
                                    'onclick' => "updateFunction()"
                                ])
                            ?>
                        </div>
                    </div>
                    <div class="wrs_row wrs_padding d-none">
                        <div class="wrs_col wrs_s12">
                            <div id="container_tab">
                                <input id="tab_1" type="radio" name="tab_group" checked="checked" onclick="displayPreview()" />
                                <label for="tab_1">Preview</label>

                                <input id="tab_2" type="radio" name="tab_group" onclick="displayHTML()" />
                                <label for="tab_2">HTML</label>

                                <div id="content_tab">
                                    <div class="wrs_div_box wrs_preview" id="preview_div"></div>
                                    <div class="wrs_div_box wrs_preview wrs_code" id="htmlcode_div" style="display:none;">
                                        <p>Press the update button to see the HTML</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            <br>
        </div>

    </div>


    <!-- Prism JS script to beautify the HTML code -->
    <script type="text/javascript" src="/backend/web/editor/js/prism.js"></script>

    <!-- WIRIS script -->
    <script type="text/javascript" src="/backend/web/editor/js/wirislib.js"></script>

    <!-- Google Analytics -->
    <script src="/backend/web/editor/js/google_analytics.js"></script>

    <script>
        if(typeof urlParams !== 'undefined') {
            var selectLang = document.getElementById('lang_select');
            selectLang.value = urlParams[1];
        }
    </script>

</div>
