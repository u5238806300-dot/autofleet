<?php

declare(strict_types=1);

namespace app\services\ai;

use GuzzleHttp\Client;
use yii\helpers\Json;
use Exception;
use Yii;

final readonly class AiRecommendationService
{
    public function __construct(
        private Client $httpClient,
        private string $apiKey
    )
    {
    }

    /**
     * @return array<int, string> Lista sugerowanych nazw części
     */
    public function getRecommendationsFromLlm(string $prompt): array
    {
        if (empty($this->apiKey)) {
            // Fallback na środowisku deweloperskim bez klucza
            return ['Brak klucza API. Mock: Cewka zapłonowa', 'Mock: Świece zapłonowe'];
        }

        try {
            $response = $this->httpClient->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Jesteś ekspertem motoryzacyjnym.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.3, // Niska temperatura dla bardziej precyzyjnych i powtarzalnych wyników
                ],
            ]);

            $body = Json::decode($response->getBody()->getContents());
            $content = $body['choices'][0]['message']['content'] ?? '[]';

            return Json::decode($content);
        } catch (Exception $e) {
            Yii::error('Błąd komunikacji z LLM: ' . $e->getMessage());
            return [];
        }
    }
}
