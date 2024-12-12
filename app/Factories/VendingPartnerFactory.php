<?php

namespace App\Factories;

use App\Enums\VendingPartners;
use App\Interfaces\VendingPartnerInterface;
use App\Services\Partners\Bap;
use App\Services\Partners\Shaggo;

class VendingPartnerFactory
{
    /**
     * @param string $partner
     * @return VendingPartnerInterface
     */
    public static function create(string $partner): VendingPartnerInterface
    {
        return match ($partner) {
            VendingPartners::BAP->value => new Bap(),
            VendingPartners::SHAGGO->value => new Shaggo(),
            default => throw new \InvalidArgumentException('Invalid Vending Partner')
        };
    }
}
