<?php

return [
    'bootstrap' => [
        \common\bootstrap\MailerBootstrap::class,
        \common\bootstrap\NotificationBootstrap::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'mutex' => \yii\mutex\MysqlMutex::class,
            'tableName' => '{{%queue}}',
        ],
    ],
];
