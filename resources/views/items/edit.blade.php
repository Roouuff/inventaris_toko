@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<h4 class="mb-3">Edit Barang</h4>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('items.update', $item) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('items._form')
        </form>
    </div>
</div>
@endsection