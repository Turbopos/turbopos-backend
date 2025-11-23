<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserExistsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user;

        if (!$user) {
            throw new HttpResponseException(response()->json([
                'message' => "Unauthorized",
            ], Response::HTTP_UNAUTHORIZED));
        }

        return $next($request);
    }
}
