<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyHealthToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('health.token');

        if (empty($expected)) {
            return $next($request);
        }

        $provided = $request->header('X-Health-Token') ?? $request->query('token');

        if (! is_string($provided) || ! hash_equals($expected, $provided)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
