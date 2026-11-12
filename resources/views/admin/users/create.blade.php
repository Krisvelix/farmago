<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/admin/dashboard">
            Farmago Admin
        </a>
        <a href="/admin/users" class="btn btn-outline-light btn-sm">
            Kembali
        </a>
    </div>
</nav>

<div class="container mt-5">

    <div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 600px;">

        <h3 class="fw-bold mb-2">Tambah Pengguna</h3>
        <p class="text-muted mb-4">Masukkan informasi akun pengguna baru.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/users" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role Pengguna</label>
                <select name="role" class="form-select" required>
                    <option value="">Pilih Role</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="pharmacy" {{ old('role') == 'pharmacy' ? 'selected' : '' }}>Apotek</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Status Akun</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">
                Simpan Pengguna
            </button>

        </form>

    </div>

</div>

</body>
</html>