<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['distributor', 'user', 'purchaseOrderDetails.product']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->distributor_id) {
            $query->where('distributor_id', $request->distributor_id);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('kode', 'like', '%' . $search . '%');
        }

        if ($request->transaction_at_from) {
            $query->where('transaction_at', '>=', $request->transaction_at_from);
        }

        if ($request->transaction_at_to) {
            $query->where('transaction_at', '<=', $request->transaction_at_to);
        }

        $query->orderBy('transaction_at', 'desc');

        $limit = $request->get('limit', 10);
        $purchaseOrders = $query->paginate($limit);

        return response()->json($this->paginatedResponse($purchaseOrders, 'purchase_orders'));
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['distributor', 'user', 'purchaseOrderDetails.product'])->findOrFail($id);
        return response()->json(['purchase_order' => $purchaseOrder]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'user_id' => 'required|exists:users,id',
            'ppn' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0',
            'transaction_at' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.harga_pokok' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['harga_pokok'] * $item['jumlah'];
            }

            $total = $subtotal + $request->ppn - $request->diskon;

            $purchaseOrder = PurchaseOrder::create([
                'kode' => uniqid('PO-'),
                'distributor_id' => $request->distributor_id,
                'user_id' => $request->user_id,
                'ppn' => $request->ppn,
                'subtotal' => $subtotal,
                'diskon' => $request->diskon,
                'total' => $total,
                'status' => PurchaseOrder::STATUS_PENDING,
                'transaction_at' => $request->transaction_at,
            ]);

            foreach ($request->items as $item) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['harga_pokok'] * $item['jumlah'],
                ]);
            }
        });

        return response()->json(['message' => 'Purchase order created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $request->validate([
            'distributor_id' => 'sometimes|exists:distributors,id',
            'user_id' => 'sometimes|exists:users,id',
            'ppn' => 'sometimes|numeric|min:0',
            'diskon' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,cancelled',
            'transaction_at' => 'sometimes|date',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.harga_pokok' => 'required_with:items|numeric|min:0',
            'items.*.jumlah' => 'required_with:items|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $data = $request->only(['distributor_id', 'user_id', 'ppn', 'diskon', 'status', 'transaction_at']);

            if ($request->has('items')) {
                // Recalculate subtotal
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['harga_pokok'] * $item['jumlah'];
                }
                $data['subtotal'] = $subtotal;
                $data['total'] = $subtotal + ($request->ppn ?? $purchaseOrder->ppn) - ($request->diskon ?? $purchaseOrder->diskon);

                // Delete old details and create new
                $purchaseOrder->purchaseOrderDetails()->delete();
                foreach ($request->items as $item) {
                    PurchaseOrderDetail::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $item['product_id'],
                        'harga_pokok' => $item['harga_pokok'],
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $item['harga_pokok'] * $item['jumlah'],
                    ]);
                }
            } else {
                // If no items, just update fields, but recalculate total if ppn or diskon changed
                if ($request->has('ppn') || $request->has('diskon')) {
                    $ppn = $request->ppn ?? $purchaseOrder->ppn;
                    $diskon = $request->diskon ?? $purchaseOrder->diskon;
                    $data['total'] = $purchaseOrder->subtotal + $ppn - $diskon;
                }
            }

            $purchaseOrder->update($data);
        });

        return response()->json(['message' => 'Purchase order updated successfully']);
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return response()->json(['message' => 'Purchase order deleted successfully']);
    }
}