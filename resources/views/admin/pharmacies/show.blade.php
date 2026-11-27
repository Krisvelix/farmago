@extends('admin.layouts.app')

@section('title', 'Detail Apotek')

@section('content')
    <div class="fg-page-head"><div class="fg-crumb"><a href="{{ url('/admin/pharmacies') }}">Verifikasi apotek</a><i class="bi bi-chevron-right"></i> Detail</div><h1>Detail Pendaftaran Apotek</h1><p>Periksa informasi apotek sebelum melakukan verifikasi.</p></div>

    <div class="fg-card fg-card-pad mx-auto" style="max-width: 820px;">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3"><span class="fg-avatar" style="width: 52px; height: 52px; border-radius: 14px; background: var(--fg-pine); font-size: 1.3rem;"><i class="bi bi-shop"></i></span><div><h2 class="h4 mb-1">{{ $pharmacy->pharmacy_name }}</h2><div class="fg-muted">{{ $pharmacy->owner_name }}</div></div></div>
            @if ($pharmacy->verification_status === 'approved') <span class="fg-pill ok">Disetujui</span>
            @elseif ($pharmacy->verification_status === 'rejected') <span class="fg-pill out">Ditolak</span>
            @else <span class="fg-pill wait">Menunggu verifikasi</span> @endif
        </div>

        <div class="row g-3">
            <div class="col-md-6"><div class="fg-note h-100 mt-0"><i class="bi bi-person"></i><div><small class="fg-muted d-block">Pemilik</small><strong>{{ $pharmacy->owner_name }}</strong></div></div></div>
            <div class="col-md-6"><div class="fg-note h-100 mt-0"><i class="bi bi-envelope"></i><div><small class="fg-muted d-block">Email akun</small><strong>{{ $pharmacy->user->email }}</strong></div></div></div>
            <div class="col-md-6"><div class="fg-note h-100 mt-0"><i class="bi bi-telephone"></i><div><small class="fg-muted d-block">Nomor telepon</small><strong>{{ $pharmacy->phone }}</strong></div></div></div>
            <div class="col-md-6"><div class="fg-note h-100 mt-0"><i class="bi bi-geo-alt"></i><div><small class="fg-muted d-block">Alamat</small><strong>{{ $pharmacy->address }}</strong></div></div></div>
        </div>

        @if ($pharmacy->rejection_reason)
            <div class="fg-flash fg-flash-error mt-4 mb-0"><i class="bi bi-exclamation-circle-fill"></i><div><strong>Alasan penolakan</strong><div>{{ $pharmacy->rejection_reason }}</div></div></div>
        @endif

        @if ($pharmacy->verification_status === 'pending')
            <div class="fg-section-head"><h2 class="fg-section-title">Tindakan Verifikasi</h2></div>
            <div class="row g-3">
                <div class="col-md-5"><form action="{{ url('/admin/pharmacies/' . $pharmacy->id . '/approve') }}" method="POST">@csrf<button type="submit" class="fg-btn w-100" onclick="return confirm('Yakin ingin menyetujui pendaftaran apotek ini?')"><i class="bi bi-check-circle"></i> Setujui pendaftaran</button></form></div>
                <div class="col-md-7"><form action="{{ url('/admin/pharmacies/' . $pharmacy->id . '/reject') }}" method="POST">@csrf<label class="fg-form-label">Alasan penolakan</label><textarea name="rejection_reason" class="form-control mb-2" rows="3" placeholder="Tuliskan alasan penolakan" required>{{ old('rejection_reason') }}</textarea><button type="submit" class="fg-btn w-100" style="background: var(--fg-red); border-color: var(--fg-red);" onclick="return confirm('Yakin ingin menolak pendaftaran apotek ini?')"><i class="bi bi-x-circle"></i> Tolak pendaftaran</button></form></div>
            </div>
        @endif
    </div>
@endsection
