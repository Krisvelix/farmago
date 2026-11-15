@extends('pharmacy.layouts.app')

@section('title', 'Pendaftaran Apotek')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Pendaftaran Apotek</h3>
    <p class="text-muted">Lengkapi informasi apotek untuk mengajukan verifikasi kepada Admin Farmago.</p>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($pharmacy)
    <div class="alert alert-info">
        Status pendaftaran: <strong>{{ ucfirst($pharmacy->verification_status) }}</strong>
    </div>

    @if ($pharmacy->verification_status === 'rejected' && $pharmacy->rejection_reason)
        <div class="alert alert-danger">
            Alasan penolakan: {{ $pharmacy->rejection_reason }}
        </div>
    @endif
@endif

<div class="card border-0 shadow-sm p-4" style="max-width: 650px;">
    <form action="{{ route('pharmacy.registration.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Apotek</label>
            <input type="text" name="pharmacy_name" class="form-control"
                value="{{ old('pharmacy_name', $pharmacy?->pharmacy_name) }}"
                placeholder="Contoh: Apotek Sehat" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Pemilik / Penanggung Jawab</label>
            <input type="text" name="owner_name" class="form-control"
                value="{{ old('owner_name', $pharmacy?->owner_name) }}"
                placeholder="Masukkan nama penanggung jawab" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-control"
                value="{{ old('phone', $pharmacy?->phone) }}"
                placeholder="Contoh: 081234567890" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Alamat Apotek</label>
            <textarea name="address" class="form-control" rows="4"
                placeholder="Masukkan alamat lengkap apotek"
                required>{{ old('address', $pharmacy?->address) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success w-100">
            {{ $pharmacy ? 'Perbarui Pendaftaran' : 'Kirim Pendaftaran' }}
        </button>
    </form>
</div>
@endsection
