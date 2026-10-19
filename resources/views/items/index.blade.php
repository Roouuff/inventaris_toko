@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="m-0">Daftar Barang</h4>
    <a href="{{ route('items.create') }}" class="btn btn-primary">+ Tambah Barang</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover m-0">
        <thead class="table-light">
            <tr>
                <th style="width: 70px;">No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Stok</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name }}</td>
                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-end">{{ $item->stock }}</td>
                <td>
                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus barang ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada barang.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection