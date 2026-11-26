<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0c3a2d">
    <title>@yield('title') - Farmago</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
</head>
<body class="fg-body d-flex flex-column min-vh-100">

    @php
        $nav = [
            ['route' => 'customer.dashboard',  'match' => 'customer.dashboard',   'label' => 'Beranda',   'short' => 'Beranda',  'icon' => 'bi-house-door'],
            ['route' => 'customer.medicines',  'match' => 'customer.medicines*',  'label' => 'Cari Obat', 'short' => 'Cari Obat','icon' => 'bi-search'],
            ['route' => 'customer.pharmacies', 'match' => 'customer.pharmacies',  'label' => 'Apotek',    'short' => 'Apotek',   'icon' => 'bi-shop'],
            ['route' => 'customer.requests',   'match' => 'customer.requests',    'label' => 'Riwayat',   'short' => 'Riwayat',  'icon' => 'bi-clock-history'],
        ];
        $userName = Auth::user()->name;
        $initial = mb_strtoupper(mb_substr($userName, 0, 1));
    @endphp

    <header class="fg-topbar">
        <div class="container fg-container">
            <div class="inner">
                <a class="fg-brand" href="{{ route('customer.dashboard') }}">
                    <span class="mark"><i class="bi bi-capsule-pill"></i></span>
                    Farmago
                </a>

                <ul class="fg-nav">
                    @foreach ($nav as $n)
                        <li>
                            <a href="{{ route($n['route']) }}" class="{{ request()->routeIs($n['match']) ? 'active' : '' }}">
                                <i class="bi {{ $n['icon'] }}"></i> {{ $n['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="fg-user dropdown">
                    <button class="btn-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="fg-avatar">{{ $initial }}</span>
                        <span class="d-none d-sm-inline">{{ \Illuminate\Support\Str::limit($userName, 18) }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-bold">{{ $userName }}</div>
                            <div class="small fg-muted">{{ Auth::user()->email }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ url('/logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="container fg-container py-4 py-lg-5 flex-grow-1">
        @if (session('success'))
            <div class="fg-flash" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"
                        onclick="this.closest('.fg-flash').remove()"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="fg-footer">
        Farmago &middot; Find Medicine, Find Pharmacy
    </footer>

    {{-- Navigasi bawah untuk HP --}}
    <nav class="fg-bottomnav" aria-label="Menu utama">
        @foreach ($nav as $n)
            <a href="{{ route($n['route']) }}" class="{{ request()->routeIs($n['match']) ? 'active' : '' }}">
                <i class="bi {{ $n['icon'] }}"></i>
                {{ $n['short'] }}
            </a>
        @endforeach
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
