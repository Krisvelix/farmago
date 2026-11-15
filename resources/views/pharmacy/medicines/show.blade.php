@extends('pharmacy.layouts.app')

@section('title', 'Detail Obat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">{{ $medicine->name }}</h3>
        <p class="text-muted mb-0">Detail informasi dan riwayat permintaan konfirmasi.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm p-4 mb-4">
    <div class="row g-4">
        <div class="col-md-6">
            <p class="text-muted small mb-1">Nama Obat</p>
            <h5 class="fw-bold">{{ $medicine->name }}</h5>

            <p class="text-muted small mb-1 mt-3">Kategori</p>
            <span class="badge bg-light text-dark border">{{ $medicine->category ?? 'Umum' }}</span>

            <p class="text-muted small mb-1 mt-3">Harga Satuan</p>
            <h5 class="text-success fw-bold">Rp {{ number_format($medicine->price, 0, ',', '.') }}
                <small class="text-muted fs-6 fw-normal">/ {{ $medicine->unit }}</small>
            </h5>
        </div>

        <div class="col-md-6">
            <p class="text-muted small mb-1">Stok</p>
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold mb-0">{{ $medicine->stock }} {{ $medicine->unit }}</h5>
                @if ($medicine->stock <= 0)
                    <span class="badge bg-danger">Habis</span>
                @elseif ($medicine->stock <= 10)
                    <span class="badge bg-warning text-dark">Menipis</span>
                @else
                    <span class="badge bg-success">Aman</span>
                @endif
            </div>

            <p class="text-muted small mb-1 mt-3">Status</p>
            @if ($medicine->is_available && $medicine->stock > 0)
                <span class="badge bg-success">Tersedia untuk Customer</span>
            @else
                <span class="badge bg-secondary">Tidak Tersedia / Disembunyikan</span>
            @endif

            <p class="text-muted small mb-1 mt-3">Total Permintaan Masuk</p>
            <strong>{{ $medicine->confirmation_requests_count }} permintaan</strong>
        </div>
    </div>

    @if ($medicine->description)
        <hr>
        <p class="text-muted small mb-1">Deskripsi</p>
        <p class="mb-0">{{ $medicine->description }}</p>
    @endif
</div>

<h5 class="fw-bold mb-3">Riwayat Permintaan Konfirmasi</h5>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentRequests as $req)
                    <tr>
                        <td>{{ $req->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $req->customer->name ?? '-' }}</td>
                        <td>{{ $req->quantity }} {{ $medicine->unit }}</td>
                        <td>{{ $req->note ?? '-' }}</td>
                        <td>
                            @if ($req->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($req->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada permintaan konfirmasi untuk obat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
