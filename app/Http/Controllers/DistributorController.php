<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $query = Distributor::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $limit = $request->get('limit', 10);
        $distributors = $query->paginate($limit);

        return response()->json($this->paginatedResponse($distributors, 'distributors'));
    }

    public function show($id)
    {
        $distributor = Distributor::with(['products'])->findOrFail($id);

        return response()->json([
            'distributor' => $distributor,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureIsAdmin();

        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'whatsapp' => 'nullable|string',
        ]);

        $distributor = Distributor::create($request->all());

        return response()->json([
            'distributor' => $distributor,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->ensureIsAdmin();

        $distributor = Distributor::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string',
            'alamat' => 'sometimes|string',
            'telepon' => 'sometimes|string',
            'whatsapp' => 'nullable|string',
        ]);

        $distributor->update($request->all());

        return response()->json([
            'distributor' => $distributor,
        ]);
    }

    public function destroy($id)
    {
        $this->ensureIsAdmin();

        $distributor = Distributor::findOrFail($id);
        $distributor->delete();

        return response()->json(['message' => 'Distributor deleted successfully']);
    }
}
