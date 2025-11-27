<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\SalesTransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesTransaction::with(['customer', 'user', 'details']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
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
        $salesTransactions = $query->paginate($limit);

        return response()->json($this->paginatedResponse($salesTransactions, 'sales_transactions'));
    }

    public function show($id)
    {
        $salesTransaction = SalesTransaction::with(['customer', 'user', 'details', 'details.product'])->findOrFail($id);
        return response()->json(['sales_transaction' => $salesTransaction]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'ppn' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'transaction_at' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.ppn' => 'nullable|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $products = Product::whereIn('id', collect($request->items)->pluck('product_id')->toArray())->get();
            $items = $request->items;

            $subtotal = 0;
            foreach ($items as $i => $item) {
                $product = $products->where('id', $item['product_id'])->first();

                $items[$i]['harga_pokok'] = $product->harga_pokok;
                $items[$i]['harga_jual'] = $product->harga_jual;
                $items[$i]['subtotal'] = $product->harga_jual * $item['jumlah'];

                $ppn = @$item['ppn'] ?? 0;
                $diskon = @$item['diskon'] ?? 0;

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

            $salesTransaction = SalesTransaction::create([
                'kode' => uniqid('ST-'),
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'ppn' => $ppn,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'status' => SalesTransaction::STATUS_PENDING,
                'transaction_at' => $request->transaction_at ?? Carbon::now(),
            ]);

            foreach ($items as $item) {
                SalesTransactionDetail::create([
                    'sales_transaction_id' => $salesTransaction->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'harga_jual' => $item['harga_jual'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'ppn' => @$item['ppn'] ?? 0,
                    'diskon' => @$item['diskon'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        });

        return response()->json(['message' => 'Sales transaction created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ppn' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.ppn' => 'nullable|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            $salesTransaction = SalesTransaction::findOrFail($id);

            $products = Product::whereIn('id', collect($request->items)->pluck('product_id')->toArray())->get();
            $items = $request->items;

            $subtotal = 0;
            foreach ($items as $i => $item) {
                $product = $products->where('id', $item['product_id'])->first();

                $items[$i]['harga_pokok'] = $product->harga_pokok;
                $items[$i]['harga_jual'] = $product->harga_jual;
                $items[$i]['subtotal'] = $product->harga_jual * $item['jumlah'];

                $ppn = @$item['ppn'] ?? 0;
                $diskon = @$item['diskon'] ?? 0;

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

            $salesTransaction->update([
                'user_id' => Auth::id(),
                'ppn' => $ppn,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
            ]);

            $salesTransaction->salesTransactionDetails()->delete();

            foreach ($items as $item) {
                SalesTransactionDetail::create([
                    'sales_transaction_id' => $salesTransaction->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'harga_jual' => $item['harga_jual'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'ppn' => @$item['ppn'] ?? 0,
                    'diskon' => @$item['diskon'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        });

        return response()->json(['message' => 'Sales transaction updated successfully']);
    }

    public function destroy($id)
    {
        $salesTransaction = SalesTransaction::findOrFail($id);
        $salesTransaction->delete();

        return response()->json(['message' => 'Sales transaction deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $salesTransaction = SalesTransaction::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $salesTransaction->update(['status' => $request->status]);

        return response()->json(['message' => 'Sales transaction status updated successfully']);
    }
}
