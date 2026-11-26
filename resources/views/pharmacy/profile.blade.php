@extends('pharmacy.layouts.app')

@section('title', 'Profil Apotek')

@section('content')
    <div class="fg-page-head">
        <h1>Profil Apotek</h1>
        <p>Kelola identitas, alamat operasional, dan kontak penanggung jawab apotek.</p>
    </div>

    @if ($errors->any())
        <div class="fg-flash" style="background: var(--fg-red-bg); color: var(--fg-red); border-color: #f6c8c2;" role="alert">
            <i class="bi bi-exclamation-octagon-fill text-danger"></i>
            <div>
                <strong class="d-block mb-1">Gagal menyimpan perubahan:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (!$pharmacy)
        <div class="fg-card fg-empty p-5">
            <div class="ico"><i class="bi bi-shop"></i></div>
            <h3>Apotek Belum Terdaftar</h3>
            <p>Silakan lengkapi pendaftaran data apotek Anda terlebih dahulu agar dapat diverifikasi oleh admin.</p>
            <a href="{{ route('pharmacy.registration') }}" class="fg-btn" style="width: auto;">
                <i class="bi bi-pencil-square"></i> Mulai Pendaftaran
            </a>
        </div>
    @else
        <div class="row g-4">
            {{-- Kartu Info Apotek (Identik dengan format card Customer) --}}
            <div class="col-lg-4">
                <div class="fg-card fg-pharm">
                    <div class="head">
                        <div class="ico"><i class="bi bi-shop"></i></div>
                        <div>
                            <h3>{{ $pharmacy->pharmacy_name }}</h3>
                            @if ($pharmacy->verification_status === 'approved')
                                <span class="fg-verified"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                            @elseif ($pharmacy->verification_status === 'pending')
                                <span class="fg-tag warning"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</span>
                            @else
                                <span class="fg-tag danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="row-i">
                        <i class="bi bi-person-badge"></i>
                        <span>PJ: {{ $pharmacy->owner_name }}</span>
                    </div>

                    <div class="row-i">
                        <i class="bi bi-telephone"></i>
                        <span>{{ $pharmacy->phone }}</span>
                    </div>

                    <div class="row-i">
                        <i class="bi bi-geo-alt"></i>
                        <span>{{ $pharmacy->address }}</span>
                    </div>

                    <div class="foot">
                        <span class="fg-muted small">Akun Login</span>
                        <span class="text-dark">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Form Edit Profil --}}
            <div class="col-lg-8">
                <div class="fg-card fg-card-pad">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="fg-stat-ico d-inline-flex align-items-center justify-content-center"
                             style="width: 36px; height: 36px; border-radius: 10px; background: var(--fg-mint); color: var(--fg-green-d);">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h2 class="h5 fw-bold mb-0">Ubah Data Apotek</h2>
                    </div>

                    <form action="{{ route('pharmacy.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="fg-form-label">Nama Apotek <span class="text-danger">*</span></label>
                            <input type="text" name="pharmacy_name" class="form-control"
                                   value="{{ old('pharmacy_name', $pharmacy->pharmacy_name) }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="fg-form-label">Nama Penanggung Jawab / Apoteker <span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" class="form-control"
                                       value="{{ old('owner_name', $pharmacy->owner_name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="fg-form-label">Nomor Telepon Operasional <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $pharmacy->phone) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fg-form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="4" required>{{ old('address', $pharmacy->address) }}</textarea>
                            <div class="fg-form-hint">Cantumkan jalan, nomor, kecamatan, dan kota agar mudah ditemukan customer.</div>
                        </div>

                        <button type="submit" class="fg-btn" style="width: auto;">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
