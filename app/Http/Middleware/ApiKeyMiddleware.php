<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Missing'
            ], 401);
        }

        $key = ApiKey::where('api_key', $apiKey)
                    ->where('status', 1)
                    ->first();

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key'
            ], 401);
        }

        $key->update([
            'last_used_at' => now()
        ]);

        return $next($request);
    }
}