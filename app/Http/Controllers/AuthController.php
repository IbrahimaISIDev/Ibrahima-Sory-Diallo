<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMysql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthServiceInterface;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                // 'password' => Hash::make(str_random(24)),
            ]);
        }

        Auth::login($user);

        return redirect('/home');
    }

    public function login(Request $request)
    {
        Log::info('Login method called', ['request' => $request->all()]);

        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        return $this->authService->login($credentials);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->validate(['refresh_token' => 'required'])['refresh_token'];
        return $this->authService->refresh($refreshToken);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->bearerToken());
    }
}
