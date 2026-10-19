<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventaris Toko')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Inventaris Toko</a>
        <!-- <a class="nav-link text-white me-auto ms-3" href="{{ route('categories.index') }}">Kategori</a> -->
        <div class="me-auto ms-3 d-flex gap-3">
            <a class="nav-link text-white" href="{{ route('items.index') }}">Barang</a>
            <a class="nav-link text-white" href="{{ route('categories.index') }}">Kategori</a>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>