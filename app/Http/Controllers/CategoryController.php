<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('master.index', [
            'title' => 'Categories',
            'subtitle' => 'Kelola kategori dokumen arsip.',
            'records' => Category::latest()->paginate(10),
            'columns' => ['Name', 'Description'],
            'renderRow' => fn (Category $category) => [$category->name, $category->description],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create([
            'name' => $request->name,
            'description' => $request->description ?? '-',
        ]);
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update([
            'name' => $request->name,
            'description' => $request->description ?? '-',
        ]);
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Proteksi Integritas Data
        if ($category->archives()->exists()) {
            return back()->withErrors(['error' => "Kategori tidak bisa dihapus karena masih digunakan oleh {$category->archives()->count()} arsip."]);
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
