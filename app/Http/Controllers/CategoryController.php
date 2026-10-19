<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request));

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Aturan integritas relasi: tolak jika masih punya barang
        $jumlah = $category->items()->count();

        if ($jumlah > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih memiliki {$jumlah} barang. Hapus atau pindahkan barangnya terlebih dahulu.");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(
            ['name' => ['required', 'string', 'max:255']],
            [
                'name.required' => 'Nama kategori tidak boleh kosong.',
                'name.max'      => 'Nama kategori maksimal 255 karakter.',
            ]
        );
    }
}