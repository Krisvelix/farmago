<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Apotek - Farmago</title>

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
        <h3 class="fw-bold">Dashboard Apotek</h3>

        <p class="text-muted">
            Selamat datang, {{ Auth::user()->name }}!
        </p>
        @php
    $pharmacy = Auth::user()->pharmacy;
@endphp

<div class="card shadow-sm border-0 p-3 mb-4">
    <h5 class="fw-bold">Status Verifikasi Apotek</h5>

    @if (!$pharmacy)
        <span class="badge bg-secondary">Belum Mendaftar</span>
        <p class="text-muted mt-2 mb-0">
            Silakan lengkapi pendaftaran apotek terlebih dahulu.
        </p>

    @elseif ($pharmacy->verification_status === 'pending')
        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>

    @elseif ($pharmacy->verification_status === 'approved')
        <span class="badge bg-success">Disetujui</span>

    @elseif ($pharmacy->verification_status === 'rejected')
        <span class="badge bg-danger">Ditolak</span>
        <p class="text-muted mt-2 mb-0">
            Alasan: {{ $pharmacy->rejection_reason }}
        </p>
    @endif
</div>

        <div class="row mt-4">
            <div class="col-md-4 mb-3">
    <div class="card shadow-sm border-0 p-3">
        <h5>Pendaftaran Apotek</h5>
        <p class="text-muted">
            Daftarkan informasi apotek dan lihat status verifikasi dari Admin.
        </p>
        <a href="{{ url('/pharmacy/registration') }}" class="btn btn-success">
            Lihat Pendaftaran
        </a>
    </div>
</div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Kelola Data Obat</h5>
                    <p class="text-muted">
                        Tambahkan, ubah, dan hapus informasi obat.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Kelola Stok dan Harga</h5>
                    <p class="text-muted">
                        Perbarui jumlah stok dan harga obat.
                    </p>
                    <button class="btn btn-success" disabled>
                        Segera Hadir
                    </button>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3">
                    <h5>Permintaan Customer</h5>
                    <p class="text-muted">
                        Lihat dan tanggapi permintaan konfirmasi obat.
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