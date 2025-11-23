<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureIsAdmin();

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama' => 'required|string',
            'role' => 'required|in:admin,mekanik,operator',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'nama' => $request->nama,
            'role' => $request->role,
        ]);

        return response()->json([
            'user' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->ensureIsAdmin();

        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:8',
            'nama' => 'sometimes|string',
            'role' => 'sometimes|in:admin,mekanik,operator',
        ]);

        $data = $request->only(['email', 'nama', 'role']);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $this->ensureIsAdmin();

        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
