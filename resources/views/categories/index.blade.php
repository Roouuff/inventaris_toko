@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="m-0">Daftar Kategori</h4>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover m-0">
        <thead class="table-light">
            <tr>
                <th style="width: 70px;">No</th>
                <th>Nama Kategori</th>
                <th>Jumlah Barang</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->items_count }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada kategori.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection