@extends('pharmacy.layouts.app')

@section('title', $medicine->name)

@section('content')
    @php
        $cat = strtolower($medicine->category ?? '');
        $icon = match (true) {
            str_contains($cat, 'demam') || str_contains($cat, 'nyeri') => 'bi-thermometer-half',
            str_contains($cat, 'antibiotik')                            => 'bi-shield-plus',
            str_contains($cat, 'antihistamin')                          => 'bi-flower1',
            str_contains($cat, 'maag')                                  => 'bi-droplet-half',
            str_contains($cat, 'suplemen') || str_contains($cat, 'vitamin') => 'bi-brightness-high',
            default                                                     => 'bi-capsule-pill',
        };
    @endphp

    <nav class="fg-crumb" aria-label="Breadcrumb">
        <a href="{{ route('pharmacy.medicines.index') }}"><i class="bi bi-arrow-left"></i> Data Obat</a>
        <span>/</span>
        <span>{{ $medicine->name }}</span>
    </nav>

    {{-- Detail Card Header --}}
    <div class="fg-card fg-detail-head mb-4">
        <div>
            <div class="ico"><i class="bi {{ $icon }}"></i></div>
            <span class="cat">{{ $medicine->category ?? 'Umum' }}</span>
            <h1>{{ $medicine->name }}</h1>
            <p>{{ $medicine->description ?? 'Tidak ada deskripsi rinci untuk obat ini.' }}</p>
        </div>

        <div class="d-flex flex-column gap-2 align-items-end">
            <div class="fg-pricebox w-100">
                <div class="l">Harga Jual</div>
                <div class="v">Rp {{ number_format($medicine->price, 0, ',', '.') }}</div>
                <div class="s">per {{ $medicine->unit }}</div>
            </div>
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="fg-btn-sm outline w-100 justify-content-center">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>

    {{-- Kartu Parameter Status & Stok --}}
    <div class="fg-stats mb-4">
        <div class="fg-stat">
            <div class="ico"><i class="bi bi-boxes"></i></div>
            <div class="num">{{ $medicine->stock }} <small class="fs-6 fg-muted">{{ $medicine->unit }}</small></div>
            <div class="lbl">
                @if ($medicine->stock <= 0)
                    <span class="fg-pill out">Stok Habis</span>
                @elseif ($medicine->stock <= 10)
                    <span class="fg-pill low">Stok Menipis</span>
                @else
                    <span class="fg-pill ok">Stok Aman</span>
                @endif
            </div>
        </div>

        <div class="fg-stat">
            <div class="ico"><i class="bi bi-eye"></i></div>
            <div class="num" style="font-size: 1.3rem;">
                {{ $medicine->is_available ? 'Aktif' : 'Disembunyikan' }}
            </div>
            <div class="lbl">
                @if ($medicine->is_available && $medicine->stock > 0)
                    <span class="fg-tag success">Tampil di Customer</span>
                @else
                    <span class="fg-tag neutral">Tidak Dapat Dipesan</span>
                @endif
            </div>
        </div>

        <div class="fg-stat">
            <div class="ico"><i class="bi bi-chat-left-dots"></i></div>
            <div class="num">{{ $medicine->confirmation_requests_count }}</div>
            <div class="lbl">Permintaan Customer Masuk</div>
        </div>
    </div>

    {{-- Riwayat Permintaan Konfirmasi Terkait --}}
    <div class="fg-section-head">
        <h2 class="fg-section-title">Riwayat Konfirmasi Customer</h2>
        <span class="small fg-muted">5 permintaan terakhir untuk obat ini</span>
    </div>

    @if ($recentRequests->isEmpty())
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-chat-dots"></i></div>
            <h3>Belum ada riwayat permintaan</h3>
            <p>Saat customer meminta konfirmasi ketersediaan obat ini, datanya akan tampil di sini.</p>
        </div>
    @else
        <div class="fg-card" style="overflow: hidden;">
            <table class="fg-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Customer</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentRequests as $req)
                        <tr>
                            <td>
                                <div>{{ $req->created_at->translatedFormat('d M Y') }}</div>
                                <small class="fg-muted">{{ $req->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong class="d-block">{{ $req->customer->name ?? '-' }}</strong>
                                <span class="small fg-muted">{{ $req->customer->email ?? '' }}</span>
                            </td>
                            <td class="fw-bold">{{ $req->quantity }} {{ $medicine->unit }}</td>
                            <td>
                                <span class="small fg-muted">{{ $req->note ?? 'Tanpa catatan' }}</span>
                            </td>
                            <td>
                                @if ($req->status === 'pending')
                                    <span class="fg-pill low">Menunggu</span>
                                @elseif ($req->status === 'available')
                                    <span class="fg-pill ok">Tersedia</span>
                                @else
                                    <span class="fg-pill out">Tidak Ada</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
