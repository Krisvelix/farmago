<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Apotek - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/admin/dashboard">
            Farmago Admin
        </a>

        <a href="/admin/pharmacies" class="btn btn-outline-light btn-sm">
            Kembali
        </a>
    </div>
</nav>

<div class="container mt-5 mb-5">

    <div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 700px;">

        <h3 class="fw-bold mb-2">Detail Pendaftaran Apotek</h3>

        <p class="text-muted mb-4">
            Periksa informasi apotek sebelum melakukan verifikasi.
        </p>

        <table class="table">
            <tr>
                <th width="40%">Nama Apotek</th>
                <td>{{ $pharmacy->pharmacy_name }}</td>
            </tr>

            <tr>
                <th>Nama Pemilik</th>
                <td>{{ $pharmacy->owner_name }}</td>
            </tr>

            <tr>
                <th>Email Akun</th>
                <td>{{ $pharmacy->user->email }}</td>
            </tr>

            <tr>
                <th>Nomor Telepon</th>
                <td>{{ $pharmacy->phone }}</td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td>{{ $pharmacy->address }}</td>
            </tr>

            <tr>
                <th>Status Verifikasi</th>
                <td>
                    @if ($pharmacy->verification_status === 'approved')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif ($pharmacy->verification_status === 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    @endif
                </td>
            </tr>

            @if ($pharmacy->rejection_reason)
                <tr>
                    <th>Alasan Penolakan</th>
                    <td>{{ $pharmacy->rejection_reason }}</td>
                </tr>
            @endif
        </table>

        @if ($pharmacy->verification_status === 'pending')

            <hr>

            <h5 class="fw-bold mb-3">Tindakan Verifikasi</h5>

            <form action="{{ url('/admin/pharmacies/' . $pharmacy->id . '/approve') }}"
                  method="POST" class="mb-3">

                @csrf

                <button type="submit"
                        class="btn btn-success w-100"
                        onclick="return confirm('Yakin ingin menyetujui pendaftaran apotek ini?')">
                    Setujui Pendaftaran
                </button>

            </form>

            <form action="{{ url('/admin/pharmacies/' . $pharmacy->id . '/reject') }}"
                  method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan</label>

                    <textarea name="rejection_reason"
                              class="form-control"
                              rows="3"
                              placeholder="Tuliskan alasan penolakan"
                              required>{{ old('rejection_reason') }}</textarea>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <button type="submit"
                        class="btn btn-danger w-100"
                        onclick="return confirm('Yakin ingin menolak pendaftaran apotek ini?')">
                    Tolak Pendaftaran
                </button>

            </form>

        @endif

    </div>

</div>

</body>
</html>