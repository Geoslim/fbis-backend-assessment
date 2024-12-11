<?php

namespace App\Services;

use App\Enums\VendingPartners;
use App\Services\Partners\Bap;
use App\Services\Partners\Shaggo;

class VendingService
{
    private Bap|Shaggo $vendingServicePartner;

    public function __construct()
    {
        $this->vendingServicePartner = match (config('partner.default_vending_partner')) {
            VendingPartners::BAP->value => new Bap(),
            VendingPartners::SHAGGO->value => new Shaggo(),
            default => throw new \InvalidArgumentException()
        };

    }

    public function vendingAirtime(mixed $array)
    {
        return $this->vendingServicePartner->vendAirtime($array);
    }
}
