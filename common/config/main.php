<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Tashkent',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'telegram' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '7669085237:AAGEA_fecmq4iflTftYYTYCGpag61LOM4TM',
        ],
        'telegram2' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '7908668770:AAHdhpGZK2oUGrtMQjMAXWoGirYXwMBaMqU',
        ],
        'ikAmoCrm' => [
            'class' => 'common\components\AmoCrmClient',
        ],
        'ikPdf' => [
            'class' => 'common\components\Contract',
        ],
    ],
];
