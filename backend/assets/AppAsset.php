<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/edu-assets';
    public $css = [
        'css/icon-bootstrap.css',
        'css/alert.css',
        'css/main.css',
        'css/sweet.css',
        'css/sidebar.css',
        'css/button.css',
        'css/checkbox.css',
        'css/table.css',
        'css/form.css',
        'css/style.css',
        'css/list.css',
        'css/user-payment.css',
        'css/login.css',
        'css/permission.css',
    ];

    public $js = [
//        '../editor/tinymce6/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',
//        '../editor/tinymce6/tinymce.min.js',
//        '../editor/js/prism.js',
//        '../editor/js/wirislib.js',
//        '../editor/js/google_analytics.js',
        'js/alert.js',
        'js/sidebar1.js',
//        'js/jquery.masknumber.js',
//        'js/mask.js',
        'js/sweet.js',
        'js/file-upload.js',
        'js/form.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
