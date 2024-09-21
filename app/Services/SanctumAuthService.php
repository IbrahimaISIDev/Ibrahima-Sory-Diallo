<?php

namespace App\Services;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class SanctumAuthService implements AuthServiceInterface
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'user' => $user,
                'access_token' => $token,
            ];
        }
        return null;
    }

    public function refresh(string $refreshToken)
    {
        // Sanctum doesn't use refresh tokens by default, but we can implement a custom refresh mechanism
        $user = $this->authRepository->findUserByRefreshToken($refreshToken);
        if (!$user) {
            return null;
        }
        $this->revokeTokens($user);
        return $this->generateTokens($user);
    }

    public function logout(string $token)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->where('token', hash('sha256', $token))->delete();
        }
        Auth::logout();
        return true;
    }

    private function revokeTokens($user)
    {
        $user->tokens()->delete();
    }

    private function generateTokens($user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }
}