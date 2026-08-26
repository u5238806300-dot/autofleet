<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

// Załadowanie autoloadera Composera
require_once __DIR__ . '/../vendor/autoload.php';

// Załadowanie rdzenia Yii2
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Inicjalizacja aplikacji Yii (ustawia alias @app oraz konfigurację)
$config = require __DIR__ . '/../config/test.php';
new \yii\web\Application($config);
