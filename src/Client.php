<?php

namespace HCloud\HSign;

use HCloud\HSign\Exceptions\HSignException;

class Client
{
    private string $apiKey;
    private string $baseUrl;

    /**
     * Par défaut, on pointe vers ton instance hCloud.
     */
    public function __construct(string $apiKey, string $baseUrl = 'https://sign.hcloud.fr/api/v2')
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Instancie un nouveau Template prêt à être configuré.
     */
    public function getTemplateById(int $templateId): Template
    {
        return new Template($this, $templateId);
    }

    /**
     * Moteur cURL interne.
     * @throws \JsonException
     * @throws HSignException
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        $ch = curl_init();

        $headers = [
            'Authorization: ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Tu peux passer à false en dev si besoin
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (!empty($data) && strtoupper($method) !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new HSignException("Erreur de connexion hSign : " . $error);
        }

        $decodedResponse = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        // Gestion des erreurs HTTP
        if ($httpCode < 200 || $httpCode >= 300) {
            $errorMessage = $decodedResponse['message'] ?? $decodedResponse['error'] ?? 'Erreur API inconnue';
            throw new HSignException("Erreur hSign (Code {$httpCode}) : {$errorMessage}");
        }

        return $decodedResponse ?? [];
    }
}