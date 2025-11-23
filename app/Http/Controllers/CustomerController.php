<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $limit = $request->get('limit', 10);
        $customers = $query->paginate($limit);

        return response()->json($this->paginatedResponse($customers, 'customers'));
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id)->with(['transports']);
        return response()->json(['customer' => $customer]);
    }

    public function store(Request $request)
    {
        $this->ensureIsAdmin();

        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'whatsapp' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $customer = Customer::create($request->all());

        return response()->json(['customer' => $customer], 201);
    }

    public function update(Request $request, $id)
    {
        $this->ensureIsAdmin();

        $customer = Customer::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string',
            'alamat' => 'sometimes|string',
            'telepon' => 'sometimes|string',
            'whatsapp' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return response()->json(['customer' => $customer]);
    }

    public function destroy($id)
    {
        $this->ensureIsAdmin();

        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

