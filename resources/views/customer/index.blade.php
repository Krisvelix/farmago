<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Farmago</a>

            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="fw-bold">Dashboard Customer</h3>

        <p class="text-muted">
            Selamat datang, {{ Auth::user()->name }}!
        </p>

        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Cari Obat</h5>
                    <p class="text-muted">
                        Temukan informasi obat yang kamu butuhkan.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Daftar Apotek</h5>
                    <p class="text-muted">
                        Lihat apotek yang terdaftar di Farmago.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Permintaan Konfirmasi</h5>
                    <p class="text-muted">
                        Pantau permintaan konfirmasi ketersediaan obat.
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