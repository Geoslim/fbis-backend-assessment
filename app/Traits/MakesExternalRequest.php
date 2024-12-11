<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait MakesExternalRequest
{
    public int $timeout = 30;
    public string $baseUrl;
    public array $headers;

    /**
     * @param string $url
     * @param string $method
     * @param array $payload
     * @return mixed
     * @throws \Exception
     */
    protected function makeHttpRequest(string $url, string $method = 'GET', array $payload = []): mixed
    {
        try {
            return Http::timeout($this->timeout)->withHeaders($this->headers)
                ->$method($this->baseUrl . '/' . $url, $payload);

        } catch (\Exception $e) {
            Log::error('HTTP request exception:', [
                'url' => $this->baseUrl . '/' . $url,
                'payload' => $payload,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
