<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\Vehicle;
use yii\filters\RateLimiter;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

final class VehicleController extends ActiveController
{
    public $modelClass = Vehicle::class;

    /**
     * @return array|array[]|\class-string[]
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::class,
        ];
        return $behaviors;
    }
}
