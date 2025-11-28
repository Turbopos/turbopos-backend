<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use App\Models\SalesTransactionDetail;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function profitLossItem(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $query = SalesTransactionDetail::with('product', 'product.category', 'salesTransaction')
            ->whereHas('salesTransaction', function ($q) use ($request) {
                if ($request->month) {
                    $q->whereYear('transaction_at', '=', date('Y', strtotime($request->month)))
                        ->whereMonth('transaction_at', '=', date('m', strtotime($request->month)));
                }
            });

        $details = $query->get();

        $aggregated = $details->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;
            $totalJumlah = $group->sum('jumlah');
            $avgHargaPokok = $group->avg('harga_pokok');
            $avgHargaJual = $group->avg('harga_jual');
            $labaRugi = ($avgHargaJual - $avgHargaPokok) * $totalJumlah;

            return [
                'nama_barang' => $product->nama,
                'jumlah' => $totalJumlah,
                'satuan' => $product->satuan,
                'harga_beli' => $avgHargaPokok,
                'harga_jual' => $avgHargaJual,
                'laba_rugi' => $labaRugi,
            ];
        })->values();

        $limit = $request->get('limit', 10);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $aggregated->forPage($request->get('page', 1), $limit),
            $aggregated->count(),
            $limit,
            $request->get('page', 1)
        );

        return response()->json($this->paginatedResponse($paginatedData, 'profit_loss_items'));
    }

    public function profitLossCategory(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $query = SalesTransactionDetail::with('product.category', 'salesTransaction')
            ->whereHas('salesTransaction', function ($q) use ($request) {
                if ($request->month) {
                    $q->whereYear('transaction_at', '=', date('Y', strtotime($request->month)))
                        ->whereMonth('transaction_at', '=', date('m', strtotime($request->month)));
                }
            });

        $details = $query->get();

        $aggregated = $details->groupBy('product.category_id')->map(function ($group) {
            $category = $group->first()->product->category;
            $totalLabaRugi = $group->sum(function ($detail) {
                return ($detail->harga_jual - $detail->harga_pokok) * $detail->jumlah;
            });

            return [
                'kategori' => $category->nama,
                'total_laba_rugi' => $totalLabaRugi,
            ];
        })->values();

        $limit = $request->get('limit', 10);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $aggregated->forPage($request->get('page', 1), $limit),
            $aggregated->count(),
            $limit,
            $request->get('page', 1)
        );

        return response()->json($this->paginatedResponse($paginatedData, 'profit_loss_categories'));
    }

    public function stockReport(Request $request)
    {
        $query = Product::with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $query->where('jenis', Product::JENIS_BARANG);

        $limit = $request->get('limit', 10);
        $products = $query->paginate($limit);

        $report = $products->getCollection()->map(function ($product) {
            return [
                'nama_barang' => $product->nama,
                'jumlah' => $product->stok,
                'satuan' => $product->satuan,
                'stok' => $product->stok,
            ];
        });

        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $report,
            $products->total(),
            $products->perPage(),
            $products->currentPage(),
            ['path' => $products->path(), 'pageName' => $products->getPageName()]
        );

        return response()->json($this->paginatedResponse($paginatedData, 'stock_reports'));
    }

    public function purchaseOrderReport(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'distributor_id' => 'nullable|exists:distributors,id',
        ]);

        $query = PurchaseOrderDetail::with('product', 'purchaseOrder')
            ->whereHas('purchaseOrder', function ($q) use ($request) {
                if ($request->month) {
                    $q->whereYear('transaction_at', '=', date('Y', strtotime($request->month)))
                        ->whereMonth('transaction_at', '=', date('m', strtotime($request->month)));
                }
                if ($request->distributor_id) {
                    $q->where('distributor_id', $request->distributor_id);
                }
            });

        $details = $query->get();

        $aggregated = $details->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;
            $totalJumlah = $group->sum('jumlah');
            $hargaPokok = $group->first()->harga_pokok;
            $subTotal = $group->sum('subtotal');
            $total = $group->sum('total');

            return [
                'nama_barang' => $product->nama,
                'jumlah' => $totalJumlah,
                'satuan' => $product->satuan,
                'harga_pokok' => $hargaPokok,
                'sub_total' => $subTotal,
                'total' => $total,
            ];
        })->values();

        $limit = $request->get('limit', 10);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $aggregated->forPage($request->get('page', 1), $limit),
            $aggregated->count(),
            $limit,
            $request->get('page', 1)
        );

        return response()->json($this->paginatedResponse($paginatedData, 'purchase_order_reports'));
    }

    public function salesTransactionReport(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $query = SalesTransactionDetail::with('product', 'salesTransaction')
            ->whereHas('salesTransaction', function ($q) use ($request) {
                if ($request->month) {
                    $q->whereYear('transaction_at', '=', date('Y', strtotime($request->month)))
                        ->whereMonth('transaction_at', '=', date('m', strtotime($request->month)));
                }
            })
            ->whereHas('product', function ($q) use ($request) {
                if ($request->category_id) {
                    $q->where('category_id', $request->category_id);
                }
            });

        $details = $query->get();

        $aggregated = $details->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;
            $totalJumlah = $group->sum('jumlah');
            $hargaJual = $group->first()->harga_jual;
            $subTotal = $group->sum('subtotal');
            $total = $group->sum('total');

            return [
                'nama_barang' => $product->nama,
                'jumlah' => $totalJumlah,
                'satuan' => $product->satuan,
                'harga_jual' => $hargaJual,
                'sub_total' => $subTotal,
                'total' => $total,
            ];
        })->values();

        $limit = $request->get('limit', 10);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $aggregated->forPage($request->get('page', 1), $limit),
            $aggregated->count(),
            $limit,
            $request->get('page', 1)
        );

        return response()->json($this->paginatedResponse($paginatedData, 'sales_transaction_reports'));
    }
}
