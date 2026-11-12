<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Farmago Admin</a>

            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="fw-bold">Dashboard Admin</h3>

        <p class="text-muted">
            Selamat datang, {{ Auth::user()->name }}!
        </p>

        <div class="row mt-4">

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Kelola Pengguna</h5>
                    <p class="text-muted">
                        Lihat, tambah, ubah, dan kelola akun pengguna.
                    </p>
                    <a href="{{ url('/admin/pharmacies') }}" class="btn btn-success">
                      Verifikasi Apotek
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Verifikasi Apotek</h5>
                    <p class="text-muted">
                        Periksa dan verifikasi pendaftaran apotek.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Kelola Data</h5>
                    <p class="text-muted">
                        Pantau data obat dan aktivitas Farmago.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>