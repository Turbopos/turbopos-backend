<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['distributor', 'user', 'details']);

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
        $purchaseOrder = PurchaseOrder::with(['distributor', 'user', 'details', 'details.product'])->findOrFail($id);
        return response()->json(['purchase_order' => $purchaseOrder]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'ppn' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'transaction_at' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_pokok' => 'required|integer|min:1',
            'items.*.ppn' => 'nullable|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $items = $request->items;

            $subtotal = 0;
            foreach ($items as $i => $item) {
                $items[$i]['subtotal'] = $item['harga_pokok'] * $item['jumlah'];

                $ppn = @$item[$i]['ppn'] ?? 0;
                $diskon = @$item[$i]['diskon'] ?? 0;

                $ppnValue = $items[$i]['subtotal'] * ($ppn / 100);
                $diskonValue = $items[$i]['subtotal'] * ($diskon / 100);

                $items[$i]['total'] = $items[$i]['subtotal'] + $ppnValue - $diskonValue;
                $subtotal += $items[$i]['total'];
            }

            $ppn = $request->ppn ?? 0;
            $diskon = $request->diskon ?? 0;

            $ppnValue = $subtotal * ($ppn / 100);
            $diskonValue = $subtotal * ($diskon / 100);

            $total = $subtotal + $ppnValue - $diskonValue;

            $purchaseOrder = PurchaseOrder::create([
                'kode' => uniqid('PO-'),
                'distributor_id' => $request->distributor_id,
                'user_id' => Auth::id(),
                'ppn' => $ppn,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'status' => PurchaseOrder::STATUS_PENDING,
                'transaction_at' => $request->transaction_at ?? Carbon::now(),
            ]);

            foreach ($items as $item) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'ppn' => @$item['ppn'] ?? 0,
                    'diskon' => @$item['diskon'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        });

        return response()->json(['message' => 'Purchase order created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $request->validate([
            'ppn' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'transaction_at' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_pokok' => 'required|integer|min:1',
            'items.*.ppn' => 'nullable|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $items = $request->items;

            $subtotal = 0;
            foreach ($items as $i => $item) {
                $items[$i]['subtotal'] = $item['harga_pokok'] * $item['jumlah'];

                $ppn = @$item[$i]['ppn'] ?? 0;
                $diskon = @$item[$i]['diskon'] ?? 0;

                $ppnValue = $items[$i]['subtotal'] * ($ppn / 100);
                $diskonValue = $items[$i]['subtotal'] * ($diskon / 100);

                $items[$i]['total'] = $items[$i]['subtotal'] + $ppnValue - $diskonValue;
                $subtotal += $items[$i]['total'];
            }

            $ppn = $request->ppn ?? 0;
            $diskon = $request->diskon ?? 0;

            $ppnValue = $subtotal * ($ppn / 100);
            $diskonValue = $subtotal * ($diskon / 100);

            $total = $subtotal + $ppnValue - $diskonValue;

            $purchaseOrder->update([
                'user_id' => Auth::id(),
                'ppn' => $ppn,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'transaction_at' => $request->transaction_at ?? Carbon::now(),
            ]);

            PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)->delete();

            foreach ($items as $item) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'ppn' => @$item['ppn'] ?? 0,
                    'diskon' => @$item['diskon'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        });

        return response()->json(['message' => 'Purchase order updated successfully'], 201);
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return response()->json(['message' => 'Purchase order deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $purchaseOrder->update(['status' => $request->status]);

        return response()->json(['message' => 'Purchase order status updated successfully']);
    }


}
