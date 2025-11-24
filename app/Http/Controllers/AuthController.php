<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
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
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $payload = [
            'iss' => 'turbopos-backend',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * 7, // 1 jam
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function getProfile(Request $request)
    {
        return response()->json([
            'profile' => $request->user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user;

        $request->validate([
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'password' => 'sometimes|min:8',
            'nama' => 'sometimes|string',
        ]);

        $data = $request->only(['username', 'nama']);

        if ($request->has('password') && !empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'user' => $user->fresh(),
        ]);
    }
}
