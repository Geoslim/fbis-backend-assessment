<?php

namespace App\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

interface VendingPartnerInterface
{
    public function vendAirtime(array $data, Transaction|Model $transaction);
}
