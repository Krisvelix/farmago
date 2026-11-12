
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Farmago</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 450px;">

        <div class="text-center mb-4">
            <h2 class="fw-bold text-success">Farmago</h2>
            <p class="text-muted">Find Medicine, Find Pharmacy</p>
            <h5>Login ke Akun Kamu</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}" placeholder="Masukkan email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                Login
            </button>
        </form>

        <p class="text-center mt-3 mb-0">
            Belum punya akun?
            <a href="{{ url('/register') }}" class="text-success">Daftar</a>
        </p>

    </div>
</div>

</body>
</html>
