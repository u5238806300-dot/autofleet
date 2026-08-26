<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\db\Exception;

final class HealthController extends Controller
{
    /**
     * Zwraca status zdrowia aplikacji oraz stan połączenia z bazą.
     * @return array<string, mixed>
     */
    public function actionIndex(): array
    {
        $dbStatus = 'ok';
        try {
            Yii::$app->db->open();
        } catch (Exception $e) {
            $dbStatus = 'error: ' . $e->getMessage();
        }

        return [
            'status' => 'healthy',
            'timestamp' => time(),
            'php_version' => PHP_VERSION,
            'database' => $dbStatus,
        ];
    }
}
