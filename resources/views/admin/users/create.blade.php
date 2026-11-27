@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="fg-page-head"><div class="fg-crumb"><a href="{{ url('/admin/users') }}">Pengguna</a><i class="bi bi-chevron-right"></i> Tambah pengguna</div><h1>Tambah Pengguna</h1><p>Masukkan informasi akun pengguna baru.</p></div>
    <div class="fg-card fg-card-pad mx-auto" style="max-width: 680px;">
        <form action="{{ url('/admin/users') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12"><label class="fg-form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-12"><label class="fg-form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                <div class="col-md-6"><label class="fg-form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="col-md-6"><label class="fg-form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
                <div class="col-md-6"><label class="fg-form-label">Role Pengguna</label><select name="role" class="form-select" required><option value="">Pilih role</option><option value="customer" @selected(old('role') === 'customer')>Customer</option><option value="pharmacy" @selected(old('role') === 'pharmacy')>Apotek</option><option value="admin" @selected(old('role') === 'admin')>Admin</option></select></div>
                <div class="col-md-6"><label class="fg-form-label">Status Akun</label><select name="is_active" class="form-select" required><option value="1" @selected(old('is_active', '1') === '1')>Aktif</option><option value="0" @selected(old('is_active') === '0')>Nonaktif</option></select></div>
            </div>
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4"><a href="{{ url('/admin/users') }}" class="fg-btn ghost">Batal</a><button type="submit" class="fg-btn"><i class="bi bi-check2"></i> Simpan pengguna</button></div>
        </form>
    </div>
@endsection
