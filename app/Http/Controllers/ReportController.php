<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        });

        $totalKeseluruhan = $aggregated->sum('laba_rugi');

        return response()->json([
            'report' => $aggregated->values(),
            'total_keseluruhan' => $totalKeseluruhan,
        ]);
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
        });

        $totalKeseluruhan = $aggregated->sum('total_laba_rugi');

        return response()->json([
            'report' => $aggregated->values(),
            'total_keseluruhan' => $totalKeseluruhan,
        ]);
    }

    public function stockReport(Request $request)
    {
        $query = Product::with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $query->where('jenis', Product::JENIS_BARANG);

        $products = $query->get();

        $report = $products->map(function ($product) {
            return [
                'nama_barang' => $product->nama,
                'jumlah' => $product->stok,
                'satuan' => $product->satuan,
                'stok' => $product->stok,
            ];
        });

        return response()->json([
            'report' => $report,
        ]);
    }
}
