<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Data untuk DataTables
    public function data(Request $request)
{
    $query = Product::with('category');

    // Kasir hanya boleh lihat produk yang aktif
    if (Auth::check() && Auth::user()->role_id == 2) {
        $query->where('is_active', 1);
    }

    return datatables()->of($query)
        ->addColumn('category_name', function($row) {
            return $row->category->category_name ?? '-';
        })
        ->addColumn('action', function($row) {
            // Hanya admin yang bisa edit/hapus
            if (Auth::check() && Auth::user()->role_id == 1) {
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

    public function index()
    {
        $categories = Category::all();
        return view('admin.products.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'product_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'product_price' => 'required|numeric',
            'product_description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $path = $request->file('product_photo')->store('products', 'public');

        $product = Product::create([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_photo' => $path,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'product_id' => $product->id]);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'product_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'product_price' => 'required|numeric',
            'product_description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['category_id','product_name','product_price','product_description','is_active']);

        if ($request->hasFile('product_photo')) {
            if ($product->product_photo && Storage::disk('public')->exists($product->product_photo)) {
                Storage::disk('public')->delete($product->product_photo);
            }
            $data['product_photo'] = $request->file('product_photo')->store('products', 'public');
        }

        $product->update($data);

        return response()->json(['message' => 'Produk berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->product_photo && Storage::disk('public')->exists($product->product_photo)) {
            Storage::disk('public')->delete($product->product_photo);
        }
        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
