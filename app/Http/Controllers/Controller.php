<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    public function ensureIsAdmin()
    {
        $user = Auth::user();

        if (!$user->is_admin) {
            throw new HttpResponseException(response()->json([
                'message' => 'Unauthorized',
            ]));
        }
    }

    public function paginatedResponse(LengthAwarePaginator $data, $name = 'data')
    {
        return [
            $name => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
        ];
    }
}
