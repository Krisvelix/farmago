@extends('pharmacy.layouts.app')

@section('title', 'Profil Apotek')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Profil Apotek</h3>
    <p class="text-muted">Lihat dan perbarui informasi apotek Anda.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (!$pharmacy)
    <div class="card border-0 shadow-sm p-4">
        <p class="text-muted mb-3">Apotek belum didaftarkan. Silakan daftarkan apotek terlebih dahulu.</p>
        <a href="{{ route('pharmacy.registration') }}" class="btn btn-success">Daftar Sekarang</a>
    </div>
@else
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-bold">{{ $pharmacy->pharmacy_name }}</h5>
                <p class="text-muted small mb-1">PJ: {{ $pharmacy->owner_name }}</p>
                <p class="text-muted small mb-1">Telp: {{ $pharmacy->phone }}</p>
                <p class="text-muted small mb-3">{{ $pharmacy->address }}</p>

                @if ($pharmacy->verification_status === 'approved')
                    <span class="badge bg-success">Terverifikasi</span>
                @elseif ($pharmacy->verification_status === 'pending')
                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif

                <hr>

                <p class="text-muted small mb-0">Akun: {{ Auth::user()->name }}</p>
                <p class="text-muted small mb-0">Email: {{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3">Edit Profil Apotek</h5>
                <form action="{{ route('pharmacy.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Apotek</label>
                        <input type="text" name="pharmacy_name" class="form-control"
                            value="{{ old('pharmacy_name', $pharmacy->pharmacy_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Penanggung Jawab</label>
                        <input type="text" name="owner_name" class="form-control"
                            value="{{ old('owner_name', $pharmacy->owner_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $pharmacy->phone) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="4"
                            required>{{ old('address', $pharmacy->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
