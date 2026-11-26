@extends('customer.layouts.app')

@section('title', 'Riwayat Konfirmasi')

@section('content')
    <div class="fg-page-head">
        <h1>Riwayat konfirmasi</h1>
        <p>Pantau jawaban apotek atas permintaan stok yang kamu kirim.</p>
    </div>

    @if (count($requests) === 0)
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-chat-dots"></i></div>
            <h3>Belum ada permintaan</h3>
            <p>Buka detail obat lalu tekan &ldquo;Minta konfirmasi&rdquo; untuk menanyakan stok ke apotek.</p>
            <a href="{{ route('customer.medicines') }}" class="fg-btn"><i class="bi bi-search"></i> Cari obat</a>
        </div>
    @else
        {{-- HP: daftar. Desktop: tabel. --}}
        <div class="fg-card d-lg-none">
            <ul class="fg-list">
                @foreach ($requests as $r)
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

        <div class="fg-card d-none d-lg-block" style="overflow:hidden">
            <table class="fg-table">
                <thead>
                    <tr>
                        <th>Obat</th>
                        <th>Apotek</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $r)
                        <tr>
                            <td class="fw-bold">{{ $r['medicine'] }}</td>
                            <td>{{ $r['pharmacy'] }}</td>
                            <td class="fg-muted">{{ $r['date'] }}</td>
                            <td>@include('customer.requests._status', ['status' => $r['status']])</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
