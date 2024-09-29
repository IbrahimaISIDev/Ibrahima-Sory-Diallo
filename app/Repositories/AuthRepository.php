<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserMysql;
use App\Models\BlacklistedToken;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function findUserByRefreshToken(string $refreshToken)
    {
        return User::where('refresh_token', $refreshToken)->first();
    }

    public function blacklistToken(string $token, string $type)
    {
        return BlacklistedToken::create([
            'token' => $token,
            'type' => $type,
            'revoked_at' => now(),
        ]);
    }

    public function findUserByCredentials(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                return $user;
            }
        }
        
        return null;
    }

}