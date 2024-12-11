<?php

namespace App\Services\Partners;

use App\Interfaces\VendingPartnerInterface;
use App\Models\Transaction;
use App\Traits\MakesExternalRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
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
     * @param Transaction|Model $transaction
     * @return array
     * @throws Exception
     */
    public function vendAirtime(array $data, Transaction|Model $transaction): array
    {
        $payload = [
            'phone' => $data['recipient'],
            'amount' => (float)$data['amount'],
            'network' => $data['network'],
            'vend_type' => 'VTU',
            'serviceCode' => 'QAB',
            'request_id' => 'trx' . time(),
        ];

        $request = $this->makeHttpRequest('public/api/test/b2b', 'post', $payload);

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
