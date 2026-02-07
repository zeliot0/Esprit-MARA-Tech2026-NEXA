<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $geminiApiKey)
    {
        $this->client = $client;
        $this->apiKey = $geminiApiKey;
    }

    public function enhanceText(string $text): string
    {
        if (!$this->apiKey || $this->apiKey === 'VOTRE_ALE_API_GEMINI_ICI') {
            return "Note: Clé API non configurée. (Simulation : " . $text . " en plus professionnel)";
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $this->apiKey;

        try {
            $response = $this->client->request('POST', $url, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "En tant qu'expert en communication humanitaire, réécris ce texte pour une cause sociale en Tunisie pour le rendre plus percutant, professionnel et digne de confiance, tout en restant concis : " . $text]
                            ]
                        ]
                    ]
                ]
            ]);

            $result = $response->toArray();
            return $result['candidates'][0]['content']['parts'][0]['text'] ?? $text;
        } catch (\Exception $e) {
            return "Erreur IA : " . $e->getMessage();
        }
    }
}
