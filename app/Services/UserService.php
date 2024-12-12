<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserService
{
    public function __construct(public WalletService $walletService)
    {
        //
    }

    /**
     * @param array $data
     * @return User
     * @throws Throwable
     */
    public function onboardUser(array $data): User
    {
        try {
            DB::beginTransaction();

            $user = User::create($data);

            // create default wallet
            $wallet = $this->walletService->createWallet($user);

            // default wallet deposit
            $this->walletService->processCredit($wallet, config('wallet.default_balance'));

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
