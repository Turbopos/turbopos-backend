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
        $product = Product::with(['category', 'distributor'])->findOrFail($id);
        return response()->json(['product' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:barang,jasa',
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string',
            'distributor_id' => 'nullable|exists:distributors,id',
            'harga_pokok' => 'required_if:jenis,barang|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required_if:jenis,barang|integer',
            'satuan' => 'required_if:jenis,barang|string',
        ]);

        $data = $request->all();
        $data['kode'] = uniqid($this->_prefix($data['jenis']));
        $data['barcode'] = $this->generateBarcode(new BarcodeItem($data['kode'], $this->barcode_type));

        $product = Product::create($this->_cleanData($data));

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

        $product->update($this->_cleanData($data));

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

    private function _cleanData(array $data)
    {
        if ($data['jenis'] == Product::JENIS_JASA) {
            $data['harga_pokok'] = null;
            $data['stok'] = null;
            $data['satuan'] = null;
        }

        return $data;
    }

    private function _prefix(string $jenis)
    {
        return $jenis == 'barang' ? 'B-' : 'J-';
    }
}
