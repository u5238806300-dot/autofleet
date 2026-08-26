<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\Vehicle;
use app\services\ai\AiRecommendationService;
use app\services\ai\PromptGenerator;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\RateLimiter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use Yii;

final class AiController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param AiRecommendationService $aiService
     * @param PromptGenerator $promptGenerator
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly AiRecommendationService $aiService,
        private readonly PromptGenerator $promptGenerator,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array<string, array<string, class-string>>
     */
    public function behaviors(): array
    {
        return [
            'authenticator' => ['class' => HttpBearerAuth::class],
            'rateLimiter' => ['class' => RateLimiter::class],
        ];
    }

    /**
     * Endpoint: POST /api/ai/suggest-parts
     * @return array<string, mixed>
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionSuggestParts(): array
    {
        /** @var Request $request */
        $request = $this->request;

        // Bezpośrednie wywołanie, rzutowanie na string dla pełnego bezpieczeństwa typu
        $obd2Code = (string) $request->post('obd2_code', '');
        $vin = (string) $request->post('vin', '');

        if ($obd2Code === '' || $vin === '') {
            throw new BadRequestHttpException('Parametry "obd2_code" i "vin" są wymagane.');
        }

        $vehicle = Vehicle::findOne(['vin' => $vin]);
        if (!$vehicle) {
            throw new NotFoundHttpException('Nie znaleziono pojazdu o podanym numerze VIN.');
        }

        $prompt = $this->promptGenerator->generateForObd2($obd2Code, $vehicle->make, $vehicle->model);
        $suggestions = $this->aiService->getRecommendationsFromLlm($prompt);

        return [
            'vehicle' => "{$vehicle->make} {$vehicle->model}",
            'obd2_code' => strtoupper($obd2Code),
            'suggested_parts' => $suggestions,
        ];
    }
}
