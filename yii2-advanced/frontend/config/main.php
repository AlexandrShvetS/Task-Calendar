<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-frontend',

            /*'cookieValidationKey' => 'trV2shv9VrC1ebiAnYPfLQt598HxWN82',
            'enableCookieValidation' => false,*/
            'enableCsrfValidation' => false,

            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            //'enableSession' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        /*'task' => [
            'identityClass' => 'common\models\Task',
            'enableAutoLogin' => true,
            //'enableSession' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],*/
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'signup' => 'site/signup',
                'login' => 'site/login',
                'logout' => 'site/logout',
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'user', 'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'task', 'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'config', 'pluralize' => false,
                ],
            ],
            
        ]
        
    ],
    'params' => $params,
];
