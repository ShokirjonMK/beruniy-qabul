<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '/frontend/web';
    public $css = [
        [
            'href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            'integrity' => 'sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==',
            'crossorigin' => 'anonymous',
            'referrerpolicy' => 'no-referrer'
        ],
        'css/icon-bootstrap.css',
        'css/gilroy.css',
        'css/alert.css',
        'css/main.css',
        'css/form.css',
        'css/checkbox.css',
        'css/style.css',
        'css/cabinet.css',
        'css/cabinet2.css',
        'css/step.css',
    ];

    public $js = [
        'editor/tinymce6/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',
        'js/alert.js',
        'js/mask.js',
        'js/sweet.js',
        'js/file-upload.js',
        'js/form.js',
        'js/script.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
