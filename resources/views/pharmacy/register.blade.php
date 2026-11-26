@extends('pharmacy.layouts.app')

@section('title', 'Pendaftaran Apotek')

@section('content')
    <div class="fg-page-head">
        <h1>Pendaftaran Apotek</h1>
        <p>Lengkapi informasi identitas apotek untuk pengajuan verifikasi mitra kepada Admin Farmago.</p>
    </div>

    @if ($errors->any())
        <div class="fg-flash" style="background: var(--fg-red-bg); color: var(--fg-red); border-color: #f6c8c2;" role="alert">
            <i class="bi bi-exclamation-octagon-fill text-danger"></i>
            <div>
                <strong class="d-block mb-1">Terdapat kesalahan pengisian data:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($pharmacy)
        <div class="fg-card fg-card-pad mb-4">
            <div class="d-flex align-items-center gap-3">
                @if ($pharmacy->verification_status === 'approved')
                    <div class="ico d-grid place-items-center rounded-circle"
                         style="width: 44px; height: 44px; background: var(--fg-mint); color: var(--fg-green-d); font-size: 1.4rem;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Apotek Telah Terverifikasi</div>
                        <div class="small fg-muted">Apotek Anda telah aktif dan dapat ditemukan oleh customer di katalog Farmago.</div>
                    </div>
                @elseif ($pharmacy->verification_status === 'pending')
                    <div class="ico d-grid place-items-center rounded-circle"
                         style="width: 44px; height: 44px; background: var(--fg-amber-bg); color: var(--fg-amber); font-size: 1.4rem;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Menunggu Verifikasi Admin</div>
                        <div class="small fg-muted">Berkas pendaftaran sedang dalam antrean verifikasi tim Admin. Anda masih dapat memperbarui data jika terdapat perbaikan.</div>
                    </div>
                @else
                    <div class="ico d-grid place-items-center rounded-circle"
                         style="width: 44px; height: 44px; background: var(--fg-red-bg); color: var(--fg-red); font-size: 1.4rem;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 text-danger">Pendaftaran Belum Disetujui</div>
                        <div class="small fg-muted">Alasan: {{ $pharmacy->rejection_reason ?? 'Data belum sesuai ketentuan.' }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="fg-card fg-card-pad" style="max-width: 720px;">
        <form action="{{ route('pharmacy.registration.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="fg-form-label">Nama Apotek <span class="text-danger">*</span></label>
                <input type="text" name="pharmacy_name" class="form-control"
                       value="{{ old('pharmacy_name', $pharmacy?->pharmacy_name) }}"
                       placeholder="Contoh: Apotek Sehat Sentosa" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="fg-form-label">Nama Pemilik / Penanggung Jawab <span class="text-danger">*</span></label>
                    <input type="text" name="owner_name" class="form-control"
                           value="{{ old('owner_name', $pharmacy?->owner_name) }}"
                           placeholder="Contoh: apt. Budi Santoso, S.Farm" required>
                </div>

                <div class="col-md-6">
                    <label class="fg-form-label">Nomor Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $pharmacy?->phone) }}"
                           placeholder="Contoh: 081234567890" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="fg-form-label">Alamat Lengkap Apotek <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control" rows="4"
                          placeholder="Masukkan alamat lengkap apotek beserta nomor jalan dan kota..."
                          required>{{ old('address', $pharmacy?->address) }}</textarea>
            </div>

            <button type="submit" class="fg-btn" style="width: auto;">
                <i class="bi bi-send"></i> {{ $pharmacy ? 'Perbarui Berkas Pendaftaran' : 'Kirim Pendaftaran' }}
            </button>
        </form>
    </div>
@endsection
