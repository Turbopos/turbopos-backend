<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyUserExistsMiddleware
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
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $user = User::find($decoded->sub);

            if (!$user) {
                throw new UnauthorizedHttpException('Unauthorized');
            }
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Unauthorized');
        }

        return $next($request);
    }
}

