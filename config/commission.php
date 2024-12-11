<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mode of Commission Calculation
    |--------------------------------------------------------------------------
    |
    | This option determines the commission calculation mode.
    | Available values:
    | - 'percentage': Calculates commission as a percentage of the transaction amount.
    | - 'flat': Applies a fixed commission amount per transaction.
    |
    */
    'mode' => 'flat',

    /*
    |--------------------------------------------------------------------------
    | Percentage Rate
    |--------------------------------------------------------------------------
    |
    | When the mode is set to 'percentage', this value determines the percentage
    | rate used to calculate the commission. It should be a positive numeric value.
    | A rate of 2 means 2% of the transaction amount will be taken as commission.
    |
    */
    'rate' => 2,

    /*
    |--------------------------------------------------------------------------
    | Flat Rate Value
    |--------------------------------------------------------------------------
    |
    | When the mode is set to 'flat', this value represents the fixed amount
    | to be applied as commission for each transaction.
    |
    */
    'flat_rate' => 100,
];
