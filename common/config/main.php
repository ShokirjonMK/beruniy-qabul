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
            'botToken' => '8132126478:AAG0rC9gkBCokIXqiSUsDl2uMxPFXp_Xtps',
        ],
        'telegram2' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '8173823388:AAELfo6VRvhT2mUZ50jMfmwg4WBk2GpvQCs',
        ],
        'ikAmoCrm' => [
            'class' => 'common\components\AmoCrmClient',
        ],
        'ikPdf' => [
            'class' => 'common\components\Contract',
        ],
    ],
];
