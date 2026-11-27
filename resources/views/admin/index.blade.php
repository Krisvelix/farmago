@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="fg-page-head">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <div class="fg-tag info mb-2"><i class="bi bi-shield-check"></i> Panel administrasi</div>
                <h1>Dashboard Admin</h1>
                <p>Kelola pengguna dan pastikan setiap apotek di Farmago terverifikasi dengan baik.</p>
            </div>
            <div class="fg-muted small">Selamat datang, <strong>{{ Auth::user()->name }}</strong></div>
        </div>
    </div>

    <div class="fg-grid">
        <a href="{{ url('/admin/users') }}" class="fg-card fg-card-pad text-decoration-none h-100">
            <div class="fg-stat p-0 border-0 shadow-none">
                <div class="ico"><i class="bi bi-people"></i></div>
                <div class="num">Pengguna</div>
                <div class="lbl">Lihat, tambah, ubah, dan kelola akun pengguna Farmago.</div>
                <span class="fg-btn-sm success mt-3">Kelola pengguna <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>
        <a href="{{ url('/admin/pharmacies') }}" class="fg-card fg-card-pad text-decoration-none h-100">
            <div class="fg-stat p-0 border-0 shadow-none">
                <div class="ico"><i class="bi bi-shop"></i></div>
                <div class="num">Apotek</div>
                <div class="lbl">Periksa pendaftaran dan verifikasi apotek yang masuk.</div>
                <span class="fg-btn-sm success mt-3">Buka verifikasi <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>
        <div class="fg-card fg-card-pad h-100">
            <div class="fg-stat p-0 border-0 shadow-none">
                <div class="ico"><i class="bi bi-bar-chart"></i></div>
                <div class="num">Data Farmago</div>
                <div class="lbl">Ringkasan aktivitas dan pengelolaan data akan tersedia di sini.</div>
                <span class="fg-tag neutral mt-3">Segera hadir</span>
            </div>
        </div>
    </div>

    <div class="fg-note mt-4"><i class="bi bi-info-circle-fill"></i><span>Gunakan menu di atas untuk berpindah antar area administrasi.</span></div>
@endsection
