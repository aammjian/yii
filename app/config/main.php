<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'bootstrap' => ['log', 'routes'],
    'modules' => [
        'routes' => [
            'class' => 'cyneek\yii2\routes\Module',
            'routes_dir' => [
                require(__DIR__ . '/routes.php'),
            ],
        ],
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-app',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity_app', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-app',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => "@runtime/logs/" . date('Y-m') . '/' . date('d') . '/' . date('H') . "-api.log",
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->format = yii\web\Response::FORMAT_JSON;
            },
        ],
        'route' => [
            'class' => 'cyneek\yii2\routes\components\route',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        */
    ],
    'params' => $params,
];
