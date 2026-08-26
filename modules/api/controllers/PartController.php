<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\Part;
use app\models\Vehicle;
use app\repositories\PartRepositoryInterface;
use yii\data\ActiveDataProvider;
use yii\filters\RateLimiter;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Serializer;
use yii\web\NotFoundHttpException;

final class PartController extends Controller
{
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items', // Zwraca dane w postaci: { items: [...], _meta: {...} }
    ];

    /**
     * @param $id
     * @param $module
     * @param PartRepositoryInterface $partRepository
     * @param $config
     */
    public function __construct(
        $id,
        $module,
        private readonly PartRepositoryInterface $partRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array[]
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

    /**
     * @param string|null $vin
     * @return ActiveDataProvider
     */
    public function actionIndex(?string $vin = null): ActiveDataProvider
    {
        $query = Part::find();

        if ($vin) {
            $vehicle = Vehicle::findOne(['vin' => $vin]);
            if ($vehicle) {
                $query->innerJoinWith('vehicles v')->where(['v.vin' => $vin]);
            } else {
                $query->where('0=1'); // Zwraca pusto dla błędnego VIN
            }
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20, // Paginacja wymuszona na 20 elementów
            ],
        ]);
    }
}
