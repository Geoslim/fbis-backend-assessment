<?php

namespace App\Services\Partners;

use App\Interfaces\VendingPartnerInterface;
use App\Traits\MakesExternalRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Shaggo implements VendingPartnerInterface
{
    use MakesExternalRequest;

    public function __construct()
    {
        $this->baseUrl = config('shaggo.base_url');
        $this->headers = [
            'Accept' => 'application/json',
            'hashKey' => config('shaggo.api_key')
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function vendAirtime(array $data): array
    {
        $payload = [
            'phone' => $data['recipient'],
            'amount' => $data['amount'],
            'network' => $data['network'],
            'vend_type' => 'VTU',
            'serviceCode' => 'QAB',
            'request_id' => $data['reference']
        ];

        $request = $this->makeHttpRequest('public/api/test/b2b', 'post', $payload);

        return $this->handleResponse($request);
    }

    /**
     * @param string $reference
     * @return array
     * @throws Exception
     */
    public function fetchTransactionStatus(string $reference): array
    {
        $payload = [
            'serviceCode' => 'QUB',
            'reference' => $reference
        ];

        $request = $this->makeHttpRequest('public/api/test/b2b', 'post', $payload);

        return $this->handleResponse($request);
    }

    public function handleResponse($request): array
    {
        $response = $request->json();

        // weirdly returns http 200 for all requests
        if ($request->failed() || $response['status'] != Response::HTTP_OK) {
            Log::error('http request error using shaggo partner:: ', [$response]);
            return [
                'success' => false,
                'error' => $response['message'] ?? 'An error occurred'
            ];
        }

        return [
            'success' => true,
            'response' => $response
        ];
    }
}
