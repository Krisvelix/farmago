<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Farmago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('pharmacy.dashboard') }}">Farmago Apotek</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.dashboard') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.registration*') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.registration') }}">Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.profile*') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.profile') }}">Profil Apotek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.medicines*') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.medicines.index') }}">Data Obat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.stock*') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.stock') }}">Stok & Harga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.requests*') ? 'active fw-semibold' : '' }}" href="{{ route('pharmacy.requests') }}">Permintaan Konfirmasi</a>
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

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="text-center text-muted small py-3 border-top bg-white mt-5">
        Farmago &mdash; Find Medicine, Find Pharmacy
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
