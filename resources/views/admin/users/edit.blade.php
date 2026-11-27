@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="fg-page-head"><div class="fg-crumb"><a href="{{ url('/admin/users') }}">Pengguna</a><i class="bi bi-chevron-right"></i> Edit pengguna</div><h1>Edit Pengguna</h1><p>Perbarui informasi akun <strong>{{ $user->name }}</strong>.</p></div>
    <div class="fg-card fg-card-pad mx-auto" style="max-width: 680px;">
        <form action="{{ url('/admin/users/' . $user->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12"><label class="fg-form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                <div class="col-12"><label class="fg-form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                <div class="col-md-6"><label class="fg-form-label">Password Baru</label><input type="password" name="password" class="form-control"><div class="fg-form-hint">Kosongkan jika tidak ingin mengganti password.</div></div>
                <div class="col-md-6"><label class="fg-form-label">Konfirmasi Password Baru</label><input type="password" name="password_confirmation" class="form-control"></div>
                <div class="col-md-6"><label class="fg-form-label">Role Pengguna</label><select name="role" class="form-select" required><option value="customer" @selected(old('role', $user->role) === 'customer')>Customer</option><option value="pharmacy" @selected(old('role', $user->role) === 'pharmacy')>Apotek</option><option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option></select></div>
                <div class="col-md-6"><label class="fg-form-label">Status Akun</label><select name="is_active" class="form-select" required><option value="1" @selected(old('is_active', $user->is_active ? '1' : '0') === '1')>Aktif</option><option value="0" @selected(old('is_active', $user->is_active ? '1' : '0') === '0')>Nonaktif</option></select></div>
            </div>
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4"><a href="{{ url('/admin/users') }}" class="fg-btn ghost">Batal</a><button type="submit" class="fg-btn"><i class="bi bi-check2"></i> Simpan perubahan</button></div>
        </form>
    </div>
@endsection
