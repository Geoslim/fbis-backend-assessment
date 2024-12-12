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
    public function __construct(public UserService $userService)
    {
        //
    }

    /**
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function register(array $data): array
    {
        try {
            // user onboarding
            $user = $this->userService->onboardUser($data);

            return $this->generateAuthResponse($user);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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

        return $this->generateAuthResponse($user);
    }


    /**
     * @param User $user
     * @return array
     */
    private function generateAuthResponse(User $user): array
    {
        return [
            'token' => $user->createToken($user->email)->plainTextToken,
            'user' => UserResource::make($user),
        ];
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
