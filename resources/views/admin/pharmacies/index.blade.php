<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Apotek - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/admin/dashboard">
            Farmago Admin
        </a>

        <a href="/admin/dashboard" class="btn btn-outline-light btn-sm">
            Kembali
        </a>
    </div>
</nav>

<div class="container mt-5">

    <div class="mb-4">
        <h3 class="fw-bold">Verifikasi Apotek</h3>
        <p class="text-muted">
            Periksa dan kelola pendaftaran apotek Farmago.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-success">
                    <tr>
                        <th>No.</th>
                        <th>Nama Apotek</th>
                        <th>Nama Pemilik</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pharmacies as $pharmacy)

                        <tr>
                            <td>
                                {{ $pharmacies->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $pharmacy->pharmacy_name }}</td>

                            <td>{{ $pharmacy->owner_name }}</td>

                            <td>{{ $pharmacy->user->email }}</td>

                            <td>{{ $pharmacy->phone }}</td>

                            <td>
                                @if ($pharmacy->verification_status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($pharmacy->verification_status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ url('/admin/pharmacies/' . $pharmacy->id) }}"
                                   class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                Belum ada pendaftaran apotek.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

    <div class="mt-3">
        {{ $pharmacies->links('pagination::bootstrap-5') }}
    </div>

</div>

</body>
</html>