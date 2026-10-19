@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<h4 class="mb-3">Tambah Barang</h4>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('items.store') }}" method="POST" novalidate>
            @csrf
            @include('items._form')
        </form>
    </div>
</div>
@endsection