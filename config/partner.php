<?php

use App\Enums\VendingPartners;

return [
    /*
    |--------------------------------------------------------------------------
    | Vending Partner Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration option specifies the default Vending Partner to be used
    | for airtime vending operations.
    |
    */

    'default_vending_partner' => VendingPartners::BAP->value
];