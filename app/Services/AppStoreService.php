<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AppStoreService
{
    private Client $client;
    private string $baseUrl;
    private string $keyId;
    private string $issuerId;
    private string $bundleId;
    private string $privateKeyPath;

    public function __construct()
    {
        $this->keyId = config('services.app_store.key_id');
        $this->issuerId = config('services.app_store.issuer_id');
        $this->bundleId = config('services.app_store.bundle_id');
        $this->privateKeyPath = config('services.app_store.private_key_path');
        
        $environment = config('services.app_store.environment', 'sandbox');
        $this->baseUrl = $environment === 'production' 
            ? 'https://api.appstoreconnect.apple.com/v1'
            : 'https://api.appstoreconnect.apple.com/v1/sandbox';

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Verifica el estado de una transacción
     *
     * @param string $transactionId
     * @return array|null
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        try {
            $response = $this->client->get("/transactions/{$transactionId}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('App Store API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica el estado de una suscripción
     *
     * @param string $subscriptionId
     * @return array|null
     */
    public function verifySubscription(string $subscriptionId): ?array
    {
        try {
            $response = $this->client->get("/subscriptions/{$subscriptionId}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('App Store API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera un token JWT para la autenticación
     *
     * @return string
     */
    private function generateToken(): string
    {
        $header = [
            'alg' => 'ES256',
            'kid' => $this->keyId,
            'typ' => 'JWT'
        ];

        $payload = [
            'iss' => $this->issuerId,
            'iat' => time(),
            'exp' => time() + 3600,
            'aud' => 'appstoreconnect-v1',
            'bid' => $this->bundleId
        ];

        $privateKey = file_get_contents($this->privateKeyPath);
        
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        
        $signature = '';
        openssl_sign(
            $headerEncoded . '.' . $payloadEncoded,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );
        
        $signatureEncoded = $this->base64UrlEncode($signature);
        
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * Codifica una cadena en base64url
     *
     * @param string $data
     * @return string
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
} 