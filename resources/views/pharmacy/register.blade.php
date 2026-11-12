<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Apotek - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/pharmacy/dashboard">
            Farmago Apotek
        </a>
        <a href="/pharmacy/dashboard" class="btn btn-outline-light btn-sm">
            Kembali
        </a>
    </div>
</nav>

<div class="container mt-5 mb-5">

    <div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 650px;">

        <h3 class="fw-bold">Pendaftaran Apotek</h3>

        <p class="text-muted">
            Lengkapi informasi apotek untuk mengajukan verifikasi kepada Admin Farmago.
        </p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($pharmacy)
            <div class="alert alert-info">
                Status pendaftaran:
                <strong>{{ ucfirst($pharmacy->verification_status) }}</strong>
            </div>

            @if ($pharmacy->verification_status === 'rejected' && $pharmacy->rejection_reason)
                <div class="alert alert-danger">
                    Alasan penolakan: {{ $pharmacy->rejection_reason }}
                </div>
            @endif
        @endif

        <form action="{{ url('/pharmacy/registration') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Apotek</label>
                <input type="text" name="pharmacy_name" class="form-control"
                    value="{{ old('pharmacy_name', $pharmacy?->pharmacy_name) }}"
                    placeholder="Contoh: Apotek Sehat" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Pemilik / Penanggung Jawab</label>
                <input type="text" name="owner_name" class="form-control"
                    value="{{ old('owner_name', $pharmacy?->owner_name) }}"
                    placeholder="Masukkan nama penanggung jawab" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $pharmacy?->phone) }}"
                    placeholder="Contoh: 081234567890" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Alamat Apotek</label>
                <textarea name="address" class="form-control" rows="4"
                    placeholder="Masukkan alamat lengkap apotek"
                    required>{{ old('address', $pharmacy?->address) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">
                Kirim Pendaftaran
            </button>

        </form>

    </div>

</div>

</body>
</html>