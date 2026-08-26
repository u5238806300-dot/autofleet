<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=db;dbname=autofleet',
    'username' => 'autofleet_user',
    'password' => 'autofleet_password',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
