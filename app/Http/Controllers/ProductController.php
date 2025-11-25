<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\BarcodeController;
use App\Http\Controllers\Traits\BarcodeItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use BarcodeController;
    public $barcode_type = 'product';

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->distributor_id) {
            $query->where('distributor_id', $request->distributor_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        $limit = $request->get('limit', 10);
        $products = $query->paginate($limit);

        return response()->json($this->paginatedResponse($products, 'products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['product' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:barang,jasa',
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string',
            'distributor_id' => 'nullable|exists:distributors,id',
            'harga_pokok' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'satuan' => 'required|string',
        ]);

        $data = $request->all();
        $data['kode'] = uniqid();
        $data['barcode'] = $this->generateBarcode(new BarcodeItem($data['kode'], $this->barcode_type));

        $product = Product::create($data);

        return response()->json(['product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'jenis' => 'sometimes|in:barang,jasa',
            'category_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string',
            'distributor_id' => 'nullable|exists:distributors,id',
            'harga_pokok' => 'sometimes|numeric',
            'harga_jual' => 'sometimes|numeric',
            'stok' => 'sometimes|integer',
            'satuan' => 'sometimes|string',
        ]);

        if ($product->barcode && file_exists(public_path($product->barcode))) {
            unlink(public_path($product->barcode));
        }

        $data = $request->all();
        $data['barcode'] = $this->generateBarcode(new BarcodeItem($product->kode, $this->barcode_type));

        $product->update($data);

        return response()->json(['product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->barcode && file_exists(public_path($product->barcode))) {
            unlink(public_path($product->barcode));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
