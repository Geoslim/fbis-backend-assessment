<?php

namespace App\Services\Partners;

use App\Interfaces\VendingPartnerInterface;
use App\Traits\MakesExternalRequest;

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

    public function vendAirtime(array $data)
    {
        $payload = [
            'phone' => $data['recipient'],
            'amount' => (float)$data['amount'],
            'network' => $data['network'],
            'vend_type' => 'VTU',
            'serviceCode' => 'QAB',
            'request_id' => 'trx' . time(),
        ];

        return $this->makeHttpRequest('public/api/test/b2b', 'post', $payload);
    }
}
