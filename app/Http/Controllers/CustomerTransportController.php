<?php

namespace App\Http\Controllers;

use App\Models\CustomerTransport;
use Illuminate\Http\Request;

class CustomerTransportController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerTransport::query()->with(['customer']);

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('merk', 'like', '%' . $search . '%')
                    ->orWhere('no_polisi', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        $limit = $request->get('limit', 10);
        $transports = $query->paginate($limit);

        return response()->json($this->paginatedResponse($transports, 'transports'));
    }

    public function show($id)
    {
        $transport = CustomerTransport::with(['customer'])->findOrFail($id);
        return response()->json(['transport' => $transport]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'nama' => 'required|string',
            'jenis_kendaraan' => 'required|string',
            'merk' => 'required|string',
            'no_polisi' => 'required|string',
            'sn' => 'nullable|string',
        ]);

        $transport = CustomerTransport::create($request->all());

        return response()->json(['transport' => $transport], 201);
    }

    public function update(Request $request, $id)
    {
        $transport = CustomerTransport::findOrFail($id);

        $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'nama' => 'sometimes|string',
            'jenis_kendaraan' => 'sometimes|string',
            'merk' => 'sometimes|string',
            'no_polisi' => 'sometimes|string',
            'sn' => 'nullable|string',
        ]);

        $transport->update($request->all());

        return response()->json(['transport' => $transport]);
    }

    public function destroy($id)
    {
        $transport = CustomerTransport::findOrFail($id);
        $transport->delete();

        return response()->json(['message' => 'Customer transport deleted successfully']);
    }
}
