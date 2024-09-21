<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\UserFirebase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FirebaseAuthService implements AuthServiceInterface
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->auth = $factory->createAuth();
    }

    public function login(array $credentials)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($credentials['email'], $credentials['password']);
            $firebaseUser = $signInResult->data();

            $user = UserFirebase::firstOrCreate(
                ['email' => $firebaseUser['email']],
                [
                    'nom' => $firebaseUser['displayName'] ?? '',
                    'email' => $firebaseUser['email'],
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            // Generate a custom token instead of using createToken
            $customToken = $this->auth->createCustomToken($firebaseUser['uid']);

            return [
                'user' => $user,
                'access_token' => $customToken->toString(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refresh(string $refreshToken)
    {
        return null;
    }

    public function logout(string $token)
    {
        return true;
    }
}