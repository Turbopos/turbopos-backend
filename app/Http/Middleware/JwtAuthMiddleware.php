<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    private $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET', 'default-secret-key');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        try {
            if ($token) {
                $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
                $user = User::find($decoded->sub);

                Auth::setUser($user);

                $request->merge(['user' => $user]);
            }
        } catch (\Exception $e) {
            //
        }

        return $next($request);
    }
}
