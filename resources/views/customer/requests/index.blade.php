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
                            <span>Jumlah: {{ $r['quantity'] }}</span>
                            @if ($r['note'])
                                <span>Catatan: {{ $r['note'] }}</span>
                            @endif
                            @if ($r['responded_at'])
                                <span>Direspons: {{ $r['responded_at'] }}</span>
                            @endif
                            @if ($r['response_note'])
                                <span>{{ $r['response_note'] }}</span>
                            @endif
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
                            <td>
                                <span class="fw-bold">{{ $r['medicine'] }}</span>
                                <div class="small fg-muted">Jumlah: {{ $r['quantity'] }}</div>
                                @if ($r['note'])
                                    <div class="small fg-muted">Catatan: {{ $r['note'] }}</div>
                                @endif
                            </td>
                            <td>{{ $r['pharmacy'] }}</td>
                            <td class="fg-muted">{{ $r['date'] }}</td>
                            <td>
                                @include('customer.requests._status', ['status' => $r['status']])
                                @if ($r['responded_at'])
                                    <div class="small fg-muted mt-1">Direspons: {{ $r['responded_at'] }}</div>
                                @endif
                                @if ($r['response_note'])
                                    <div class="small mt-1">{{ $r['response_note'] }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection