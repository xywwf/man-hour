<?php

$params = require(__DIR__ . '/params.php');

Yii::$classMap = array_merge(Yii::$classMap, require(__DIR__ . '/classes.php'));

$config = [
    'id' => 'man-hour',
    'name' => 'Geely Auto (Shanghai) manhour system',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'on beforeRequest' => function ($event) {
        # use cookie to store language
        $l = Yii::$app->request->cookies->get('_language');
        Yii::$app->language = $l ? $l->value : 'zh-CN';
        return; 
    },
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'oeri-TZkF3NqFoqDXpV4MoPRLGbSz7Bg',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,//hide index.php
/*            
            'rules' => [
                'dashboard' => 'site/index',

                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'POST <controller:\w+>s' => '<controller>/create',
                '<controller:\w+>s' => '<controller>/index',
                
                'PUT <controller:\w+>/<id:\d+>'    => '<controller>/update',
                'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',
                '<controller:\w+>/<id:\d+>'        => '<controller>/view',
            ],
*/            
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'formatter' => [
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'dateFormat' => 'yyyy-MM-dd',
            'timeFormat' => 'HH:mm',
            'timeZone' => 'Asia/Shanghai',
            'defaultTimeZone' => 'Asia/Shanghai',
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
