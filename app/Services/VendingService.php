<?php

namespace App\Services;

use App\Enums\VendingPartners;
use App\Models\User;
use App\Services\Partners\Bap;
use App\Services\Partners\Shaggo;
use Illuminate\Contracts\Auth\Authenticatable;

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

    public function vendAirtime(array $array, Authenticatable|User $user)
    {
        return $this->vendingServicePartner->vendAirtime($array, $user);
    }
}
