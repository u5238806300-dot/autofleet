<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\Part;
use app\repositories\PartRepositoryInterface;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

final class PartController extends Controller
{
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
        return [
            'authenticator' => ['class' => HttpBearerAuth::class],
        ];
    }

    /**
     * @param string|null $vin
     * @return array
     */
    public function actionIndex(?string $vin = null): array
    {
        if ($vin) {
            return $this->partRepository->findCompatiblePartsByVin($vin);
        }

        // Zwracamy wszystko jeśli nie podano VIN (w późniejszym commicie dodamy DataProvider)
        return Part::find()->all();
    }
}
