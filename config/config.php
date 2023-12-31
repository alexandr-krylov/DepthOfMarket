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
                'GET api/0/order/view' => 'order/view',
                'GET api/0/order/my' => 'order/viewmy',
                'GET api/0/dom' => 'dom/view',
                'DELETE api/0/order' => 'order/cancel',
                'GET api/0/marketdata' => 'marketdata/view',
                'PUT api/0/order' => 'order/redemption',
                'GET api/0/volume' => 'volume/view',
            ],
        ]
    ],
];
