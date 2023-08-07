<?php

$db = require __DIR__ . '/db.php';

return [
    'id' => 'app',
    'basePath' => __DIR__ . '/../',
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@app' => __DIR__ . '/../',
    ],
    'components' => [
        'db' => $db,
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/0/order' => 'order/create',
                'GET api/0/order' => 'order/index',
                'GET api/0/order/<id>' => 'order/view',
                'GET api/0/order/my/<my_id>' => 'order/viewmy',
                'GET api/0/dom/<ticker>' => 'dom/view',
            ],
        ]
    ],
];
