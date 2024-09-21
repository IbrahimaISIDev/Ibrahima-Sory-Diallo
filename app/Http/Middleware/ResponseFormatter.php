<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\StateEnum;

class ResponseFormatter
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $content = $response->getContent();
        $decodedContent = json_decode($content, true);

        $formattedResponse = [
            'data' => $decodedContent ?? null,
            'status' => $response->isSuccessful() ? StateEnum::SUCCESS->value : StateEnum::ECHEC->value,
            'code' => $response->getStatusCode(),
            'message' => $response->isSuccessful() ? 'Opération réussie' : 'Une erreur est survenue'
        ];

        return response()->json($formattedResponse, $response->getStatusCode());
    }
}