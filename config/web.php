<?php

use app\models\User;
use app\repositories\PartRepository;
use app\repositories\PartRepositoryInterface;
use sizeg\jwt\Jwt;
use yii\caching\FileCache;
use yii\gii\Module;
use yii\log\FileTarget;
use yii\mail\MailerInterface;
use yii\mutex\MysqlMutex;
use yii\queue\db\Queue;
use yii\symfonymailer\Mailer;
use yii\web\Response;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'container' => [
        'definitions' => [
            // W przyszłości dodamy tu kolejne repozytoria i serwisy, np.:
            // \app\repositories\VehicleRepositoryInterface::class => \app\repositories\VehicleRepository::class,
        ],
        'singletons' => [
            // Mapowanie interfejsu na konkretną implementację.
            // Dzięki temu konstruktor kontrolera może wymagać PartRepositoryInterface,
            // a Yii2 automatycznie wstrzyknie instancję PartRepository.
            PartRepositoryInterface::class => PartRepository::class,
            MailerInterface::class => [
                'class' => Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'api' => [
            'class' => \app\modules\api\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'SECRET_KEY_REPLACE_ME',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                // Ręczny routing dla health-checka
                'GET api/health' => 'api/health/index',

                // Automatyczny routing RESTful dla kontrolerów
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'api/vehicle',
                        'api/part'
                    ],
                    // Yii2 domyślnie dodaje liczbę mnogą do URL (np. api/vehicles),
                ],
            ],
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'jwt' => [
            'class' => Jwt::class,
            'key' => 'super-secret-b2b-key-replace-in-prod',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => false, // Ważne dla REST API (bezciasteczkowe)
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'queue' => [
            'class' => Queue::class,
            'db' => 'db', // Komponent bazy danych
            'tableName' => '{{%queue}}', // Tabela w bazie danych
            'channel' => 'default', // Nazwa kolejki
            'mutex' => MysqlMutex::class, // Zapobiega podwójnemu wykonaniu
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
