<?php

$db = require __DIR__ . '/db.php';

return [
    'id' => 'myapp-console', 
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => $db,
    ],
];
