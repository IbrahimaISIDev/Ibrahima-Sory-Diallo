<?php

namespace App\Services;

use App\Interfaces\AuthRepositoryInterface;

class SanctumAuthService extends AuthService
{
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        parent::__construct($authRepository);
    }

    public function revokeTokens($user)
    {
        $user->tokens()->delete();
    }

    public function generateTokens($user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

}