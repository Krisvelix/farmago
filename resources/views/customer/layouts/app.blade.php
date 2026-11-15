<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Farmago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('customer.dashboard') }}">Farmago</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active fw-semibold' : '' }}" href="{{ route('customer.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.medicines*') ? 'active fw-semibold' : '' }}" href="{{ route('customer.medicines') }}">Cari Obat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.pharmacies') ? 'active fw-semibold' : '' }}" href="{{ route('customer.pharmacies') }}">Daftar Apotek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.requests') ? 'active fw-semibold' : '' }}" href="{{ route('customer.requests') }}">Riwayat Konfirmasi</a>
                    </li>
                </ul>

                <span class="navbar-text text-white me-3">{{ Auth::user()->name }}</span>
                <form action="{{ url('/logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center text-muted small py-3 border-top bg-white">
        Farmago &mdash; Find Medicine, Find Pharmacy
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
