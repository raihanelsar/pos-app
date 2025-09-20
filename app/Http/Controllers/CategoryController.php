<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required']);
        Category::create($request->only('category_name'));
        return redirect()->back()->with('success','Kategori berhasil ditambahkan');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
