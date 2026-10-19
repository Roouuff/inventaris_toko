<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // with('category') memuat kategori lewat relasi, tanpa query berulang per baris
        $items = Item::with('category')->orderBy('name')->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Item::create($this->validated($request));

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $item->update($this->validated($request));

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(
            [
                'category_id' => ['required', 'exists:categories,id'],
                'name'        => ['required', 'string', 'max:255'],
                'price'       => ['required', 'numeric', 'gt:0', 'max:9999999999'],
                'stock'       => ['required', 'integer', 'min:0'],
            ],
            [
                'category_id.required' => 'Kategori harus dipilih.',
                'category_id.exists'   => 'Kategori yang dipilih tidak tersedia.',
                'name.required'        => 'Nama barang tidak boleh kosong.',
                'price.required'       => 'Harga tidak boleh kosong.',
                'price.numeric'        => 'Harga harus berupa angka.',
                'price.gt'             => 'Harga harus lebih besar dari nol.',
                'price.max'            => 'Harga terlalu besar.',
                'stock.required'       => 'Stok tidak boleh kosong.',
                'stock.integer'        => 'Stok harus berupa bilangan bulat.',
                'stock.min'            => 'Stok minimal nol.',
            ]
        );
    }
}