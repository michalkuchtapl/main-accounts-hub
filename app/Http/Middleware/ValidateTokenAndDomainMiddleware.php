<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ValidateTokenAndDomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $domain = parse_url($request->headers->get('origin'), PHP_URL_HOST);

        if (empty($domain)) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (auth()->user() instanceof User) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        //Remove all protocols and everything that is after domain
        $domain = preg_replace("/(.*:\/\/)/", '', $domain);
        $domain = preg_split("/\//", $domain);
        $domain = array_shift($domain);

        if ($domain !== auth()->user()->domain) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return $next($request);
    }
}
