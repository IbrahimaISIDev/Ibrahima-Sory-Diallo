<?php

namespace App\Services;

class AuthServiceFactory
{
    public static function create()
    {
        $authService = env('AUTH_SERVICE');

        switch ($authService) {
            case 'passport':
                return app(PassportAuthService::class);
            case 'sanctum':
                return app(SanctumAuthService::class);
            default:
                throw new \Exception("Auth service not supported");
        }
    }
}
