<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class jwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token Missing'], 401);
        }

        $decoded = Helper::decodeToken($token);
        if (!$decoded) {
            return response()->json(['error' => 'Token Invalid'], 401);
        }
        
        if ($decoded->exp < time()) {
            return response()->json(['error' => 'Token expired'], 401);
        }
        $request->merge(['user_id' => $decoded->sub]);


        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($decoded->sub != $request->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}
