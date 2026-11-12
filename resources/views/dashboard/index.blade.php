<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Farmago</title>

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
        <div class="card border-0 shadow-sm p-4">
            <h3 class="fw-bold">Selamat Datang di Farmago!</h3>

            <p class="text-muted mb-4">
                Halo, {{ Auth::user()->name }}. Selamat datang di dashboard Farmago.
            </p>

            <p class="mb-1">
                <strong>Email:</strong> {{ Auth::user()->email }}
            </p>

            <p class="mb-0">
                <strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}
            </p>
        </div>
    </div>

</body>
</html>