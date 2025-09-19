<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('products.index', compact('categories'));
    }

    public function data(Request $request)
{
    $query = Product::with('category');

    // Kasir hanya boleh lihat produk yang aktif
    if (auth()->user()->role === 'kasir') {
        $query->where('is_active', 1);
    }

    return datatables()->of($query)
        ->addColumn('category_name', function($row) {
            return $row->category->category_name ?? '-';
        })
        ->addColumn('action', function($row) {
            // Hanya admin yang bisa edit/hapus
            if (auth()->user()->role === 'admin') {
                return '
                    <a href="'.route('products.edit', $row->id).'" class="btn btn-warning btn-sm">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <form action="'.route('products.destroy', $row->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus?\')">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </form>
                ';
            }
            return '-';
        })
        ->rawColumns(['action']) // supaya tombol HTML tidak di-escape
        ->make(true);
}

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'product_name' => 'required',
            'product_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'product_price' => 'required|numeric',
            'product_description' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $photo = null;
        if ($request->hasFile('product_photo')) {
            $photo = $request->file('product_photo')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_photo' => $photo,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'product_id' => $product->id]);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'product_name' => 'required',
            'product_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'product_price' => 'required|numeric',
            'product_description' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);

        $photo = $product->product_photo;
        if ($request->hasFile('product_photo')) {
            // delete old photo if present
            if ($photo) {
                Storage::disk('public')->delete($photo);
            }
            $photo = $request->file('product_photo')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_photo' => $photo,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['message' => 'Produk berhasil diupdate']);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        // delete photo from storage if exists
        if ($product->product_photo) {
            Storage::disk('public')->delete($product->product_photo);
        }
        $product->delete();
        // If the client expects JSON (AJAX) return JSON, otherwise redirect back with a flash message
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Produk berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }
}
