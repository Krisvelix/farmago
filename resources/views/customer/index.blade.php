@extends('customer.layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
    <h3 class="fw-bold">Dashboard Customer</h3>
    <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>

    {{-- Pencarian cepat --}}
    <div class="card border-0 shadow-sm mb-4 bg-success text-white">
        <div class="card-body p-4">
            <h5 class="fw-bold">Cari obat yang kamu butuhkan</h5>
            <p class="mb-3">Cek apotek yang menyediakan, harga, dan stoknya sebelum berkunjung.</p>
            <form action="{{ route('customer.medicines') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Contoh: Paracetamol">
                <button class="btn btn-light fw-semibold" type="submit">Cari</button>
            </form>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <h5>Cari Obat</h5>
                <p class="text-muted">{{ $medicineCount }} obat tersedia untuk dicari.</p>
                <a href="{{ route('customer.medicines') }}" class="btn btn-success mt-auto">Buka</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <h5>Daftar Apotek</h5>
                <p class="text-muted">{{ $pharmacyCount }} apotek terdaftar di Farmago.</p>
                <a href="{{ route('customer.pharmacies') }}" class="btn btn-success mt-auto">Buka</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <h5>Permintaan Konfirmasi</h5>
                <p class="text-muted">Pantau jawaban apotek atas permintaanmu.</p>
                <a href="{{ route('customer.requests') }}" class="btn btn-success mt-auto">Buka</a>
            </div>
        </div>
    </div>

    {{-- Obat populer --}}
    <h5 class="fw-bold mb-3">Obat yang sering dicari</h5>
    <div class="row mb-4">
        @foreach ($popular as $m)
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $m['name'] }}</h6>
                        <p class="text-muted small mb-2">{{ $m['category'] }}</p>
                        <p class="mb-2">Mulai <strong>Rp {{ number_format($m['min_price'], 0, ',', '.') }}</strong></p>
                        <a href="{{ route('customer.medicines.show', $m['id']) }}" class="btn btn-outline-success btn-sm">Lihat detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Permintaan terbaru --}}
    <h5 class="fw-bold mb-3">Permintaan konfirmasi terbaru</h5>
    <div class="card shadow-sm border-0">
        <ul class="list-group list-group-flush">
            @foreach ($recentRequests as $r)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $r['medicine'] }}</strong>
                        <div class="small text-muted">{{ $r['pharmacy'] }} &middot; {{ $r['date'] }}</div>
                    </div>
                    @include('customer.requests._status', ['status' => $r['status']])
                </li>
            @endforeach
        </ul>
    </div>
@endsection
