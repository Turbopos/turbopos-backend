<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->search) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $categories = $query->get();

        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $this->ensureIsAdmin();

        $request->validate([
            'nama' => 'required|string|unique:categories,nama',
        ]);

        $category = Category::create($request->all());

        return response()->json(['category' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $this->ensureIsAdmin();

        $category = Category::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string|unique:categories,nama,' . $id,
        ]);

        $category->update($request->all());

        return response()->json(['category' => $category]);
    }

    public function destroy($id)
    {
        $this->ensureIsAdmin();

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

