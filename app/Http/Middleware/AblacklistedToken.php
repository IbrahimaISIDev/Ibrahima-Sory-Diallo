<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BlacklistedToken;
use Symfony\Component\HttpFoundation\Response;

class AblacklistedToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $blacklistedAccessToken = BlacklistedToken::where('token', $request->bearerToken())
                ->where('type', 'access')
                ->first();

            if ($blacklistedAccessToken) {
                return response()->json([
                    'data' => null,
                    'status' => 'error',
                    'message' => 'Token révoqué.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        return $next($request);
    }
}

