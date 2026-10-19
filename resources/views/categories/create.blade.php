@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<h4 class="mb-3">Tambah Kategori</h4>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            @include('categories._form')
        </form>
    </div>
</div>
@endsection