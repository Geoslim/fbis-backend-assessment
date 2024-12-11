<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    public function __construct(public WalletService $walletService)
    {
        //
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception|Throwable
     */
    public function register(array $data): array
    {
        DB::beginTransaction();

        $user = User::create($data);

        // create default wallet
        $wallet = $this->walletService->createWallet($user);

        // default wallet deposit
        $this->walletService->processCredit($wallet, config('wallet.default_balance'));

        $response['token'] = $user->createToken($user->email)->plainTextToken;
        $response['user'] = UserResource::make($user);

        DB::commit();

        return $response;
    }

    /**
     * @param array $data
     * @return array
     * @throws AuthException
     */
    public function login(array $data): array
    {
        $user = User::whereEmail($data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthException(
                'Authentication failed. Please verify your login information and try again.'
            );
        }

        $response['token'] = $user->createToken($user->email)->plainTextToken;
        $response['user'] = UserResource::make($user);

        return $response;
    }

    /**
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
