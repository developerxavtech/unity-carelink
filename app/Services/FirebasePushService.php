<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends push notifications via the FCM HTTP v1 API using a Google service
 * account, without pulling in the full kreait/firebase-php SDK. Exchanges
 * the service account's private key for an OAuth2 access token by
 * hand-signing a JWT with PHP's built-in openssl extension — no extra
 * composer dependency required.
 */
class FirebasePushService
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

    private const SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    /**
     * Send a push notification to a user's registered device, if any.
     * Silently no-ops (and logs) when the user has no token or Firebase
     * isn't configured yet — push delivery is best-effort and must never
     * block the request that triggered it.
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): bool
    {
        if (! $user->fcm_token) {
            return false;
        }

        return $this->send($user->fcm_token, $title, $body, $data);
    }

    public function send(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        Log::info('FirebasePushService: sending push', [
            'data' => $data,
            'deviceToken' => $deviceToken,
            'title' => $title,
            'body' => $body,
        ]);

        $projectId = config('services.firebase.project_id');

        if (! $projectId) {
            Log::warning('FirebasePushService: FIREBASE_PROJECT_ID is not configured, skipping push send.');

            return false;
        }

        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            return false;
        }

        try {
            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $deviceToken,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        // FCM data payloads must be flat string => string maps.
                        'data' => array_map('strval', $data),
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('FirebasePushService: FCM send failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('FirebasePushService: FCM send threw an exception.', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Exchange the service account credentials for a short-lived OAuth2
     * access token, cached just under its ~1 hour lifetime.
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember('firebase.access_token', now()->addMinutes(55), function () {
            $credentials = $this->loadCredentials();

            if (! $credentials) {
                return null;
            }

            $jwt = $this->buildSignedJwt($credentials);

            if (! $jwt) {
                return null;
            }

            $response = Http::asForm()->post(self::TOKEN_URL, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->failed()) {
                Log::error('FirebasePushService: OAuth2 token exchange failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json('access_token');
        });
    }

    private function loadCredentials(): ?array
    {
        $path = config('services.firebase.credentials');

        if (! $path || ! is_file($path)) {
            Log::warning('FirebasePushService: Firebase service account credentials file not found.', ['path' => $path]);

            return null;
        }

        $credentials = json_decode(file_get_contents($path), true);

        if (! isset($credentials['client_email'], $credentials['private_key'])) {
            Log::error('FirebasePushService: Firebase credentials file is missing client_email/private_key.');

            return null;
        }

        return $credentials;
    }

    private function buildSignedJwt(array $credentials): ?string
    {
        $now = time();

        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ]));

        $claims = $this->base64UrlEncode(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => self::SCOPE,
            'aud' => self::TOKEN_URL,
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $signingInput = "{$header}.{$claims}";

        $signed = openssl_sign($signingInput, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256);

        if (! $signed) {
            Log::error('FirebasePushService: Failed to sign JWT with the service account private key.');

            return null;
        }

        return $signingInput.'.'.$this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
