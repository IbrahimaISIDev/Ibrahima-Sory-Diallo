<?php

namespace App\Services;

use App\Interfaces\AuthRepositoryInterface;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class PassportAuthService extends AuthService
{
    protected $tokenRepository;
    protected $refreshTokenRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        parent::__construct($authRepository);
        $this->tokenRepository = $tokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function revokeTokens($user)
    {
        $accessToken = $user->token();
        $this->tokenRepository->revokeAccessToken($accessToken->id);
        $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessToken->id);
    }

    public function generateTokens($user)
    {
        $token = $user->createToken('auth_token')->accessToken;
        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }
}
