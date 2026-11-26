@extends('pharmacy.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $pharmacyName = $pharmacy->pharmacy_name ?? Auth::user()->name;
        $isApproved = $pharmacy && $pharmacy->verification_status === 'approved';
        $isPending = $pharmacy && $pharmacy->verification_status === 'pending';
        $isRejected = $pharmacy && $pharmacy->verification_status === 'rejected';
        $notRegistered = !$pharmacy;
    @endphp

    {{-- Hero Banner Apotek --}}
    <section class="fg-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
            <div class="hello mb-0">Panel Pengelola Apotek</div>
            <div>
                @if ($isApproved)
                    <span class="fg-tag success"><i class="bi bi-patch-check-fill"></i> Apotek Terverifikasi</span>
                @elseif ($isPending)
                    <span class="fg-tag warning"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi Admin</span>
                @elseif ($isRejected)
                    <span class="fg-tag danger"><i class="bi bi-x-circle-fill"></i> Verifikasi Ditolak</span>
                @else
                    <span class="fg-tag neutral"><i class="bi bi-info-circle"></i> Belum Mendaftar</span>
                @endif
            </div>
        </div>

        <h1>{{ $pharmacyName }}</h1>
        <p>Kelola ketersediaan katalog obat, pantau stok & harga, serta layani permintaan konfirmasi dari customer secara cepat.</p>

        @if ($notRegistered)
            <div class="mt-3">
                <a href="{{ route('pharmacy.registration') }}" class="fg-btn" style="width: auto;">
                    <i class="bi bi-file-earmark-text"></i> Mulai Pendaftaran Apotek
                </a>
            </div>
        @elseif ($isPending)
            <div class="fg-note mt-3" style="background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); color: #e6f5ee;">
                <i class="bi bi-info-circle text-warning"></i>
                <div>Pendaftaran apotek Anda sedang ditinjau oleh Admin. Fitur pengelolaan obat akan aktif otomatis setelah disetujui.</div>
            </div>
        @elseif ($isRejected)
            <div class="fg-note mt-3" style="background: rgba(246, 75, 59, 0.2); border-color: rgba(255,255,255,.3); color: #fff;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>Alasan penolakan: <strong>{{ $pharmacy->rejection_reason ?? 'Berkas pendaftaran belum lengkap.' }}</strong>. Silakan perbarui data di menu Pendaftaran.</div>
            </div>
        @endif
    </section>

    {{-- Ringkasan Statistik --}}
    <div class="fg-stats mb-4">
        <a href="{{ $isApproved ? route('pharmacy.medicines.index') : '#' }}" class="fg-stat">
            <div class="ico"><i class="bi bi-capsule"></i></div>
            <div class="num">{{ $stats['total_medicines'] }}</div>
            <div class="lbl">Total Obat Terdaftar</div>
        </a>
        <a href="{{ $isApproved ? route('pharmacy.stock', ['filter' => 'low_stock']) : '#' }}" class="fg-stat">
            <div class="ico" style="background: var(--fg-amber-bg); color: var(--fg-amber);">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="num">{{ $stats['low_stock_count'] + $stats['out_of_stock_count'] }}</div>
            <div class="lbl">Stok Menipis / Habis</div>
        </a>
        <a href="{{ $isApproved ? route('pharmacy.requests') : '#' }}" class="fg-stat">
            <div class="ico" style="background: {{ $stats['pending_requests_count'] > 0 ? 'var(--fg-amber-bg)' : 'var(--fg-mint)' }}; color: {{ $stats['pending_requests_count'] > 0 ? 'var(--fg-amber)' : 'var(--fg-green-d)' }};">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div class="num d-flex align-items-center gap-2">
                {{ $stats['pending_requests_count'] }}
                @if ($stats['pending_requests_count'] > 0)
                    <span class="fg-tag warning" style="font-size: .72rem; padding: 2px 7px;">Perlu Tindakan</span>
                @endif
            </div>
            <div class="lbl">Permintaan Menunggu</div>
        </a>
    </div>

    {{-- Menu Aksi Cepat --}}
    <div class="fg-section-head">
        <h2 class="fg-section-title">Menu Pengelolaan</h2>
        <span class="small fg-muted">Akses cepat modul apotek</span>
    </div>

    <div class="fg-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="fg-card fg-card-pad d-flex flex-column justify-content-between">
            <div>
                <div class="fg-stat-ico mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 44px; height: 44px; border-radius: 12px; background: var(--fg-mint); color: var(--fg-green-d); font-size: 1.25rem;">
                    <i class="bi bi-capsule"></i>
                </div>
                <h3 class="h5 fw-bold mb-1">Katalog Obat</h3>
                <p class="fg-muted small mb-3">Tambah, edit informasi, kategori, dan deskripsi obat.</p>
            </div>
            @if ($isApproved)
                <a href="{{ route('pharmacy.medicines.index') }}" class="fg-btn ghost"><i class="bi bi-arrow-right"></i> Kelola Obat</a>
            @else
                <button class="fg-btn" disabled>Perlu Verifikasi</button>
            @endif
        </div>

        <div class="fg-card fg-card-pad d-flex flex-column justify-content-between">
            <div>
                <div class="fg-stat-ico mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 44px; height: 44px; border-radius: 12px; background: var(--fg-mint); color: var(--fg-green-d); font-size: 1.25rem;">
                    <i class="bi bi-sliders"></i>
                </div>
                <h3 class="h5 fw-bold mb-1">Stok & Harga Cepat</h3>
                <p class="fg-muted small mb-3">Perbarui stok dan nominal harga tanpa buka edit satu per satu.</p>
            </div>
            @if ($isApproved)
                <a href="{{ route('pharmacy.stock') }}" class="fg-btn ghost"><i class="bi bi-arrow-right"></i> Update Stok</a>
            @else
                <button class="fg-btn" disabled>Perlu Verifikasi</button>
            @endif
        </div>

        <div class="fg-card fg-card-pad d-flex flex-column justify-content-between">
            <div>
                <div class="fg-stat-ico mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 44px; height: 44px; border-radius: 12px; background: var(--fg-amber-bg); color: var(--fg-amber); font-size: 1.25rem;">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <h3 class="h5 fw-bold mb-1">Permintaan Customer</h3>
                <p class="fg-muted small mb-3">Tanggapi konfirmasi ketersediaan obat dari customer.</p>
            </div>
            @if ($isApproved)
                <a href="{{ route('pharmacy.requests') }}" class="fg-btn ghost">
                    <i class="bi bi-arrow-right"></i> Lihat Permintaan
                    @if ($stats['pending_requests_count'] > 0)
                        <span class="fg-tag warning ms-1" style="font-size: .7rem; padding: 1px 6px;">{{ $stats['pending_requests_count'] }}</span>
                    @endif
                </a>
            @else
                <button class="fg-btn" disabled>Perlu Verifikasi</button>
            @endif
        </div>

        <div class="fg-card fg-card-pad d-flex flex-column justify-content-between">
            <div>
                <div class="fg-stat-ico mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 44px; height: 44px; border-radius: 12px; background: var(--fg-blue-bg); color: var(--fg-blue); font-size: 1.25rem;">
                    <i class="bi bi-shop"></i>
                </div>
                <h3 class="h5 fw-bold mb-1">Profil Apotek</h3>
                <p class="fg-muted small mb-3">Perbarui alamat, kontak, penanggung jawab, dan data legalitas.</p>
            </div>
            @if ($pharmacy)
                <a href="{{ route('pharmacy.profile') }}" class="fg-btn ghost"><i class="bi bi-arrow-right"></i> Profil Apotek</a>
            @else
                <a href="{{ route('pharmacy.registration') }}" class="fg-btn"><i class="bi bi-pencil-square"></i> Daftar Sekarang</a>
            @endif
        </div>
    </div>

    {{-- Obat Terbaru & Permintaan Terbaru --}}
    @if ($isApproved)
        <div class="row g-4 mt-2">
            {{-- Tabel Obat Terbaru --}}
            <div class="col-lg-7">
                <div class="fg-section-head mt-0">
                    <h2 class="fg-section-title">Katalog Obat Terbaru</h2>
                    <a href="{{ route('pharmacy.medicines.index') }}">Lihat semua</a>
                </div>

                @if ($recentMedicines->isEmpty())
                    <div class="fg-card fg-empty p-4">
                        <div class="ico mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;"><i class="bi bi-capsule"></i></div>
                        <h3 class="h6 fw-bold">Belum ada obat terdaftar</h3>
                        <p class="small fg-muted mb-3">Mulai tambahkan produk obat ke apotek Anda sekarang.</p>
                        <a href="{{ route('pharmacy.medicines.create') }}" class="fg-btn-sm success"><i class="bi bi-plus-lg"></i> Tambah Obat</a>
                    </div>
                @else
                    <div class="fg-card" style="overflow: hidden;">
                        <table class="fg-table">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentMedicines as $med)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pharmacy.medicines.show', $med) }}" class="fw-bold text-decoration-none text-dark d-block">
                                                {{ $med->name }}
                                            </a>
                                            <div class="small fg-muted">{{ $med->category ?? 'Umum' }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="font-family: var(--fg-display); color: var(--fg-pine);">
                                                Rp {{ number_format($med->price, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($med->stock <= 0)
                                                <span class="fg-pill out">Habis</span>
                                            @elseif ($med->stock <= 10)
                                                <span class="fg-pill low">{{ $med->stock }} {{ $med->unit }}</span>
                                            @else
                                                <span class="fg-pill ok">{{ $med->stock }} {{ $med->unit }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($med->is_available && $med->stock > 0)
                                                <span class="fg-tag success">Tersedia</span>
                                            @else
                                                <span class="fg-tag neutral">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Permintaan Konfirmasi Masuk --}}
            <div class="col-lg-5">
                <div class="fg-section-head mt-0">
                    <h2 class="fg-section-title">Permintaan Terbaru</h2>
                    <a href="{{ route('pharmacy.requests') }}">Lihat semua</a>
                </div>

                @if ($recentRequests->isEmpty())
                    <div class="fg-card fg-empty p-4">
                        <div class="ico mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;"><i class="bi bi-chat-dots"></i></div>
                        <h3 class="h6 fw-bold">Belum ada permintaan</h3>
                        <p class="small fg-muted mb-0">Permintaan ketersediaan obat dari customer akan masuk di sini.</p>
                    </div>
                @else
                    <div class="fg-card">
                        <ul class="fg-list">
                            @foreach ($recentRequests as $req)
                                <li>
                                    <span class="ico {{ $req->status === 'unavailable' ? 'out' : ($req->status === 'pending' ? 'low' : '') }}">
                                        <i class="bi {{ $req->status === 'available' ? 'bi-check2-circle' : ($req->status === 'unavailable' ? 'bi-x-circle' : 'bi-hourglass-split') }}"></i>
                                    </span>
                                    <div class="body">
                                        <strong>{{ $req->medicine->name ?? 'Obat' }}</strong>
                                        <span>{{ $req->customer->name ?? 'Customer' }} &middot; {{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div>
                                        @if ($req->status === 'pending')
                                            <span class="fg-pill low">Menunggu</span>
                                        @elseif ($req->status === 'available')
                                            <span class="fg-pill ok">Tersedia</span>
                                        @else
                                            <span class="fg-pill out">Tidak Ada</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
