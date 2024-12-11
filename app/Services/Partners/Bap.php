<?php

namespace App\Services\Partners;

use App\Enums\Status;
use App\Interfaces\VendingPartnerInterface;
use App\Traits\MakesExternalRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class Bap implements VendingPartnerInterface
{
    use MakesExternalRequest;

    public function __construct()
    {
        $this->baseUrl = config('bap.base_url');
        $this->headers = [
            'Accept' => 'application/json',
            'x-api-key' => config('bap.api_key')
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
            'amount' => (float)$data['amount'],
            'service_type' => $data['network'],
            'plan' => 'prepaid',
            'agentId' => '205',
            'agentReference' => $data['reference']
        ];

        $request = $this->makeHttpRequest('services/airtime/request', 'post', $payload);

        return $this->handleResponse($request);
    }

    /**
     * @param string $reference
     * @return array
     * @throws Exception
     */
    public function fetchTransactionStatus(string $reference): array
    {
        $request = $this->makeHttpRequest(
            'services/superagent/transaction/requery',
            'get',
            [],
            ['agentReference' => $reference]
        );

        return $this->handleResponse($request);
    }

    public function handleResponse($request): array
    {
        $response = $request->json();

        if ($request->failed() || $response['status'] == Status::ERROR->value) {
            Log::error('http request error using BAP partner:: ', [$response]);
            return [
                'success' => false,
                'error' => $response['message'] ?? 'An error occurred'
            ];
        }

        return [
            'success' => true,
            'response' => $response['data']
        ];
    }
}
