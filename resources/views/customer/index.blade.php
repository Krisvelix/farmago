@extends('customer.layouts.app')

@section('title', 'Beranda')

@section('content')
    @php
        $firstName = \Illuminate\Support\Str::of(Auth::user()->name)->before(' ');
        $suggestions = ['Paracetamol', 'Amoxicillin', 'Cetirizine', 'Antasida', 'Vitamin C'];
    @endphp

    {{-- Pencarian utama --}}
    <section class="fg-hero">
        <div class="hello">Halo, {{ $firstName }}</div>
        <h1>Obat yang kamu cari, ada di apotek mana?</h1>
        <p>Cek harga dan stok di apotek terverifikasi sebelum berangkat, jadi tidak perlu keliling.</p>

        <form action="{{ route('customer.medicines') }}" method="GET" role="search">
            <div class="fg-search">
                <i class="bi bi-search lead-ico"></i>
                <input type="search" name="q" placeholder="Nama obat, contoh: Paracetamol"
                       autocomplete="off" enterkeyhint="search" aria-label="Cari nama obat">
                <button type="submit" class="go"><span>Cari obat</span><i class="bi bi-arrow-right"></i></button>
            </div>
        </form>

        <div class="chips-label">Sering dicari</div>
        <div class="fg-chips">
            @foreach ($suggestions as $s)
                <a class="fg-chip" href="{{ route('customer.medicines', ['q' => $s]) }}">{{ $s }}</a>
            @endforeach
        </div>
    </section>

    {{-- Ringkasan --}}
    <div class="fg-stats">
        <a href="{{ route('customer.medicines') }}" class="fg-stat">
            <div class="ico"><i class="bi bi-capsule"></i></div>
            <div class="num">{{ $medicineCount }}</div>
            <div class="lbl">Obat bisa dicari</div>
        </a>
        <a href="{{ route('customer.pharmacies') }}" class="fg-stat">
            <div class="ico"><i class="bi bi-shop"></i></div>
            <div class="num">{{ $pharmacyCount }}</div>
            <div class="lbl">Apotek terdaftar</div>
        </a>
        <a href="{{ route('customer.requests') }}" class="fg-stat">
            <div class="ico"><i class="bi bi-chat-dots"></i></div>
            <div class="num">{{ count($recentRequests) }}</div>
            <div class="lbl">Permintaan terbaru</div>
        </a>
    </div>

    {{-- Obat populer --}}
    <div class="fg-section-head">
        <h2 class="fg-section-title">Obat yang sering dicari</h2>
        <a href="{{ route('customer.medicines') }}">Lihat semua</a>
    </div>
    <div class="fg-grid four">
        @foreach ($popular as $m)
            @include('customer.medicines._card', ['m' => $m])
        @endforeach
    </div>

    {{-- Permintaan terbaru --}}
    <div class="fg-section-head">
        <h2 class="fg-section-title">Permintaan konfirmasi terbaru</h2>
        <a href="{{ route('customer.requests') }}">Lihat riwayat</a>
    </div>

    @if (count($recentRequests) === 0)
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-chat-dots"></i></div>
            <h3>Belum ada permintaan</h3>
            <p>Saat kamu minta apotek mengonfirmasi stok, statusnya muncul di sini.</p>
        </div>
    @else
        <div class="fg-card">
            <ul class="fg-list">
                @foreach ($recentRequests as $r)
                    <li>
                        <span class="ico {{ $r['status'] === 'unavailable' ? 'out' : ($r['status'] === 'pending' ? 'low' : '') }}">
                            <i class="bi {{ $r['status'] === 'available' ? 'bi-check2-circle' : ($r['status'] === 'unavailable' ? 'bi-x-circle' : 'bi-hourglass-split') }}"></i>
                        </span>
                        <div class="body">
                            <strong>{{ $r['medicine'] }}</strong>
                            <span>{{ $r['pharmacy'] }} &middot; {{ $r['date'] }}</span>
                        </div>
                        @include('customer.requests._status', ['status' => $r['status']])
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
