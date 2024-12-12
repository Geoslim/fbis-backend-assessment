<?php

use App\Enums\VendingPartners;

return [
    /*
    |--------------------------------------------------------------------------
    | Vending Partner
    |--------------------------------------------------------------------------
    |
    | This configuration option specifies the default Vending Partner to be used
    | for airtime vending operations.
    | Currently supporting SHAGGO & BAP
    |
    */

    'partner' => VendingPartners::BAP->value,

        /*
    |--------------------------------------------------------------------------
    | Vending Airtime Minimum Amount
    |--------------------------------------------------------------------------
    |
    | This configuration option specifies the default Vending airtime minimum amount to be used
    | for airtime vending operations.
    |
    */

    'minimum_amount' => 10,

        /*
    |--------------------------------------------------------------------------
    | Vending Airtime Maximum Amount
    |--------------------------------------------------------------------------
    |
    | This configuration option specifies the default Vending airtime maximum amount to be used
    | for airtime vending operations.
    |
    */

    'maximum_amount' => 10000
];
