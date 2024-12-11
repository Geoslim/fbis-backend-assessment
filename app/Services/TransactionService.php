<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class TransactionService
{
    public function generateReference(): UuidInterface
    {
        return Str::uuid();
    }

    /**
     * @param int|string $walletId
     * @param int|string $userId
     * @param array $data
     * @param string $status
     * @return Transaction
     */
    public function recordTransaction(
        int|string $walletId,
        int|string $userId,
        array $data,
        string $status
    ): Transaction {
        return Transaction::create([
            'user_id' => $userId,
            'wallet_id' => $walletId,
            'type' => $data['type'],
            'reference' => $data['reference'],
            'amount' => $data['amount'],
            'network' => $data['network'],
            'partner' => $data['partner'],
            'recipient' => $data['recipient'],
            'status' => $status,
            'description' => $data['description'],
            'commission' => $data['commission'] ?? 0,
        ]);
    }
}
