@extends('pharmacy.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h3 class="fw-bold">Dashboard Apotek</h3>
<p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>

<div class="card border-0 shadow-sm p-3 mb-4">
    <h5 class="fw-bold">Status Verifikasi Apotek</h5>

    @if (!$pharmacy)
        <span class="badge bg-secondary">Belum Mendaftar</span>
        <p class="text-muted mt-2 mb-0">Silakan lengkapi pendaftaran apotek terlebih dahulu.</p>
    @elseif ($pharmacy->verification_status === 'pending')
        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
    @elseif ($pharmacy->verification_status === 'approved')
        <span class="badge bg-success">Disetujui</span>
    @elseif ($pharmacy->verification_status === 'rejected')
        <span class="badge bg-danger">Ditolak</span>
        <p class="text-muted mt-2 mb-0">Alasan: {{ $pharmacy->rejection_reason }}</p>
    @endif
</div>

<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 p-3">
            <h5>Pendaftaran Apotek</h5>
            <p class="text-muted">Daftarkan informasi apotek dan lihat status verifikasi dari Admin.</p>
            <a href="{{ route('pharmacy.registration') }}" class="btn btn-success">Lihat Pendaftaran</a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 p-3">
            <h5>Profil Apotek</h5>
            <p class="text-muted">Lihat dan perbarui informasi profil apotek Anda.</p>
            @if ($pharmacy)
                <a href="{{ route('pharmacy.profile') }}" class="btn btn-success">Lihat Profil</a>
            @else
                <button class="btn btn-success" disabled>Belum Terdaftar</button>
            @endif
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 p-3">
            <h5>Kelola Data Obat</h5>
            <p class="text-muted">Tambahkan, ubah, dan hapus informasi obat.</p>
            @if ($pharmacy && $pharmacy->verification_status === 'approved')
                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-success">Kelola Obat</a>
            @else
                <button class="btn btn-success" disabled>Perlu Verifikasi</button>
            @endif
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 p-3">
            <h5>Kelola Stok dan Harga</h5>
            <p class="text-muted">Perbarui jumlah stok dan harga obat.</p>
            @if ($pharmacy && $pharmacy->verification_status === 'approved')
                <a href="{{ route('pharmacy.stock') }}" class="btn btn-success">Kelola Stok</a>
            @else
                <button class="btn btn-success" disabled>Perlu Verifikasi</button>
            @endif
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 p-3">
            <h5>Permintaan Customer</h5>
            <p class="text-muted">Lihat dan tanggapi permintaan konfirmasi obat.</p>
            @if ($pharmacy && $pharmacy->verification_status === 'approved')
                <a href="{{ route('pharmacy.requests') }}" class="btn btn-success">
                    Lihat Permintaan
                    @if ($stats['pending_requests_count'] > 0)
                        <span class="badge bg-warning text-dark ms-1">{{ $stats['pending_requests_count'] }}</span>
                    @endif
                </a>
            @else
                <button class="btn btn-success" disabled>Perlu Verifikasi</button>
            @endif
        </div>
    </div>
</div>

@if ($pharmacy && $pharmacy->verification_status === 'approved' && $recentMedicines->isNotEmpty())
<div class="mt-2">
    <h5 class="fw-bold mb-3">Obat Terbaru Terdaftar</h5>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentMedicines as $med)
                        <tr>
                            <td><strong>{{ $med->name }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $med->category ?? 'Umum' }}</span></td>
                            <td>Rp {{ number_format($med->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($med->stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif ($med->stock <= 10)
                                    <span class="badge bg-warning text-dark">{{ $med->stock }} {{ $med->unit }}</span>
                                @else
                                    <span class="badge bg-success">{{ $med->stock }} {{ $med->unit }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($med->is_available && $med->stock > 0)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
