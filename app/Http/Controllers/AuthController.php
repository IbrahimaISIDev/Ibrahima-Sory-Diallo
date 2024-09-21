<?php

namespace App\Http\Controllers;

use App\Models\UserMysql;
use Illuminate\Http\Request;
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
        
        $user = UserMysql::where('email', $socialUser->getEmail())->first();
        
        if (!$user) {
            $user = UserMysql::create([
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
        $credentials = $request->only('email', 'password');
        $result = $this->authService->login($credentials);
        
        if ($result) {
            return response()->json($result);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');
        $result = $this->authService->refresh($refreshToken);
        
        if ($result) {
            return response()->json($result);
        }
        
        return response()->json(['error' => 'Invalid refresh token'], 401);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        $this->authService->logout($token);
        
        return response()->json(['message' => 'Successfully logged out']);
    }
}