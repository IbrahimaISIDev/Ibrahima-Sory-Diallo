<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(array $credentials)
    {
        $user = $this->authRepository->findUserByCredentials($credentials);
        if (!$user) {
            return null;
        }
        return $this->generateTokens($user);
    }

    public function refresh(string $refreshToken)
    {
        $user = $this->authRepository->findUserByRefreshToken($refreshToken);
        if (!$user) {
            return null;
        }
        $this->authRepository->blacklistToken($refreshToken, 'refresh');
        $this->revokeTokens($user);
        return $this->generateTokens($user);
    }

    public function logout(string $token)
    {
        $this->authRepository->blacklistToken($token, 'access');
        Auth::logout();
        return true;
    }

    protected function revokeTokens($user)
    {
    }

    protected function generateTokens($user)
    {
    }
}
