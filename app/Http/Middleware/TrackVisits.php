<?php

namespace App\Http\Middleware;

use App\Models\Visita;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrackVisits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! $request->expectsJson()) {
            try {
                Visita::create([
                    'path' => $request->path(),
                    'url' => $request->fullUrl(),
                    'referrer' => $request->header('referer'),
                    'ip' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    'visited_at' => Carbon::now(),
                ]);
            } catch (\Throwable $e) {
                // No romper la página si el registro falla
            }
        }

        return $response;
    }
}
