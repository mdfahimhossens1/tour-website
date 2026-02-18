<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitorSession;

class TrackVisitorSession
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Skip assets
            if ($request->is('css/*','js/*','images/*','storage/*','favicon.ico')) {
                return $response;
            }

            $ip = $request->ip();
            $userId = Auth::check() ? Auth::id() : null;

            // GeoIP lookup
            $loc = geoip($ip);

            $countryCode = $loc->iso_code ?? null;  // BD, US
            $countryName = $loc->country ?? null;   // Bangladesh
            $city        = $loc->city ?? null;

            // One row per user+ip (simple)
            VisitorSession::updateOrCreate(
                [
                    'ip_address' => $ip,
                    'user_id'    => $userId,
                ],
                [
                    'country_code' => $countryCode,
                    'country_name' => $countryName,
                    'city'         => $city,
                    'last_url'     => $request->fullUrl(),
                    'last_seen_at' => now(),
                ]
            );
        } catch (\Throwable $e) {
            // Don't break the app
        }

        return $response;
    }
}
