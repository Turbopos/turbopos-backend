<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET', 'default-secret-key');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $payload = [
            'iss' => 'turbopos-backend',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600, // 1 jam
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function getProfile(Request $request)
    {
        return response()->json($request->user);
    }
}
