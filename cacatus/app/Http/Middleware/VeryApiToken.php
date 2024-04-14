<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VeryApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('Api-Token')) {
            return response()->json([
                'error' => 'request is not authorized'
            ], 401);
        }

        $token = $request->header('Api-Token');
        $token = is_array($token) ? $token[0] : $token;

        if(ApiToken::where('api_token', $token)->first() === null) {
            return response()->json([
                'error' => 'request is not authorized'
            ], 401);
        }

        return $next($request);
    }
}
