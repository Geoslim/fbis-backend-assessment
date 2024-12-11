<?php

namespace App\Services;

use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\Wallet;

class WalletService
{
    /**
     * @param User $user
     * @return Wallet
     */
    public function createWallet(User $user): Wallet
    {
        return $user->wallet()->create();
    }

    /**
     * @param int|string $userId
     * @return mixed
     */
    public function walletQuery(int|string $userId)
    {
        return Wallet::query()->whereUserId($userId);
    }

    /**
     * Process wallet debit
     * @param Wallet $wallet
     * @param int|float $amount
     * @return void
     * @throws WalletException
     */
    public function processDebit(Wallet $wallet, int|float $amount): void
    {
        $this->validateWalletBalance($wallet, $amount);
        $this->debitWallet($wallet, $amount);
    }

    /**
     * Process wallet credit
     * @param Wallet $wallet
     * @param int|float $amount
     * @return void
     */
    public function processCredit(Wallet $wallet, int|float $amount): void
    {
        $this->creditWallet($wallet, $amount);
    }

    /**
     * @param Wallet $wallet
     * @param int|float $amount
     * @return void
     */
    private function debitWallet(Wallet $wallet, int|float $amount): void
    {
        $wallet->decrement('balance', $amount);
    }

    /**
     * @param Wallet $wallet
     * @param int|float $amount
     * @return void
     */
    private function creditWallet(Wallet $wallet, int|float $amount): void
    {
        $wallet->increment('balance', $amount);
    }

    /**
     * @param Wallet $wallet
     * @param int|float $amount
     * @return void
     * @throws WalletException
     */
    public function validateWalletBalance(Wallet $wallet, int|float $amount): void
    {
        if ($wallet->balance < $amount) {
            throw new WalletException(
                'Insufficient funds in your wallet for this transaction.'
            );
        }
    }
}
