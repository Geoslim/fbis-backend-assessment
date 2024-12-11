<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    public function __construct(public WalletService $walletService)
    {
        //
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create()->each(function (User $user) {
            DB::transaction(function () use ($user) {
                $wallet = $this->walletService->createWallet($user);
                $this->walletService->processCredit($wallet, config('wallet.default_balance'));
            });
        });
    }
}
