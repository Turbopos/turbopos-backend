<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\OpnameDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = Opname::with(['user', 'details']);

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('kode', 'like', "%{$search}%");
        }

        if ($request->transaction_at_from) {
            $query->where('transaction_at', '>=', $request->transaction_at_from);
        }

        if ($request->transaction_at_to) {
            $query->where('transaction_at', '<=', $request->transaction_at_to);
        }

        $query->orderBy('transaction_at', 'desc');

        $limit = $request->get('limit', 10);
        $opnames = $query->paginate($limit);

        return response()->json($this->paginatedResponse($opnames, 'opnames'));
    }

    public function show($id)
    {
        $opname = Opname::with(['user', 'details', 'details.product'])->findOrFail($id);

        return response()->json(['opname' => $opname]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_at' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.harga_pokok' => 'required|numeric|min:0',
            'items.*.jumlah_awal' => 'required|integer|min:0',
            'items.*.jumlah_opname' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $items = $request->items;

            $total = 0;
            foreach ($items as $i => $item) {
                $selisih = $item['jumlah_opname'] - $item['jumlah_awal'];
                $total_selisih = $selisih * $item['harga_pokok'];
                $items[$i]['selisih'] = $selisih;
                $items[$i]['total_selisih'] = $total_selisih;
                $total += $total_selisih;
            }

            $opname = Opname::create([
                'kode' => uniqid('OP-'),
                'user_id' => Auth::id(),
                'total' => $total,
                'transaction_at' => $request->transaction_at ?? Carbon::now(),
            ]);

            $products = Product::whereIn('id', collect($items)->pluck('product_id')->toArray())->get();

            foreach ($items as $item) {
                $product = $products->where('id', $item['product_id'])->first();

                if ($product->jenis == Product::JENIS_BARANG) {
                    $product->update([
                        'stok' => $item['jumlah_opname'],
                    ]);
                }

                OpnameDetail::create([
                    'opname_id' => $opname->id,
                    'product_id' => $item['product_id'],
                    'harga_pokok' => $item['harga_pokok'],
                    'jumlah_awal' => $item['jumlah_awal'],
                    'jumlah_opname' => $item['jumlah_opname'],
                    'selisih' => $item['selisih'],
                    'total_selisih' => $item['total_selisih'],
                ]);
            }
        });

        return response()->json(['message' => 'Opname created successfully'], 201);
    }
}
