<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVodFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$features): Response
    {
        // Check if ANY of the passed features is enabled (OR logic)
        foreach ($features as $feature) {
            if (config("features.vod.{$feature}")) {
                return $next($request);
            }
        }

        abort(404);
    }
}
