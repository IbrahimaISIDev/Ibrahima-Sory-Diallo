<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login(array $credentials);
    public function refresh(string $refreshToken);
    public function logout(string $token);
}
