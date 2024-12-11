<?php

namespace App\Services\Partners;

use App\Interfaces\VendingPartnerInterface;
use App\Traits\MakesExternalRequest;

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

    public function vendAirtime(array $data)
    {
        $payload = [
            'phone' => $data['recipient'],
            'amount' => (float)$data['amount'],
            'service_type' => $data['network'],
            'plan' => 'prepaid',
            'agentId' => '205',
            'agentReference' => 'trx' . time()
        ];

        return $this->makeHttpRequest('services/airtime/request', 'post', $payload);
    }
}
