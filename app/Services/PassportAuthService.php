<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use App\Interfaces\AuthRepositoryInterface;
use Laravel\Passport\RefreshTokenRepository;
use App\Interfaces\AuthServiceInterface;

class PassportAuthService implements AuthServiceInterface
{
    protected $tokenRepository;
    protected $refreshTokenRepository;
    protected $authRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->authRepository = $authRepository;
        $this->tokenRepository = $tokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->accessToken;
            return [
                'user' => $user,
                'access_token' => $token,
            ];
        }
        return null;
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

    private function revokeTokens($user)
    {
        $accessToken = $user->token();
        $this->tokenRepository->revokeAccessToken($accessToken->id);
        $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessToken->id);
    }

    private function generateTokens($user)
    {
        $token = $user->createToken('auth_token')->accessToken;
        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }
}