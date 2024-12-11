<?php

namespace App\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

interface VendingPartnerInterface
{
    public function vendAirtime(array $data): array;

    public function fetchTransactionStatus(string $reference): array;

    public function handleResponse($request): array;
}
