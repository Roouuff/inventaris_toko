@extends('layouts.app')

@section('content')
    <div class="alert alert-success">
        Login berhasil. Selamat datang, {{ auth()->user()->name }}!
    </div>
@endsection