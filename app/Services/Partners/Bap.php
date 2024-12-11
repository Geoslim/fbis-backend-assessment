<?php

namespace App\Services\Partners;

use App\Enums\Status;
use App\Interfaces\VendingPartnerInterface;
use App\Models\Transaction;
use App\Traits\MakesExternalRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
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
     * @param Transaction|Model $transaction
     * @return array
     * @throws Exception
     */
    public function vendAirtime(array $data, Transaction|Model $transaction): array
    {
        $payload = [
            'phone' => $data['recipient'],
            'amount' => (float)$data['amount'],
            'service_type' => $data['network'],
            'plan' => 'prepaid',
            'agentId' => '205',
            'agentReference' => 'trx' . time()
        ];

        $request = $this->makeHttpRequest('services/airtime/request', 'post', $payload);

        $response = $this->handleResponse($request);

        if (isset($response['error'])) {
            return $response;
        }

        // update transaction

        return  $response;
    }

    private function handleResponse($request): array
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
            'response' => $response
        ];
    }
}
