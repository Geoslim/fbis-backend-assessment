<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait MakesExternalRequest
{
    public int $timeout = 30;
    public string $baseUrl;
    public array $headers;

    protected function makeHttpRequest(string $url, string $method = 'GET', array $payload = []): array
    {
        try {
            $request = Http::timeout($this->timeout)->withHeaders($this->headers)
                ->$method($this->baseUrl . '/' . $url, $payload);

            $response = $request->json();

            if ($request->failed()) {
                Log::error('http request error:: ', [$response]);
                return [
                    'success' => false,
                    'error' => $response
                ];
            }

            return [
                'success' => true,
                'response' => $response
            ];
        } catch (\Exception $e) {
            Log::error('HTTP request exception:', [
                'url' => $this->baseUrl . '/' . $url,
                'payload' => $payload,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
