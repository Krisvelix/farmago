@extends('customer.layouts.app')

@section('title', $medicine['name'])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.medicines') }}" class="text-success">Cari Obat</a></li>
            <li class="breadcrumb-item active">{{ $medicine['name'] }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h3 class="fw-bold">{{ $medicine['name'] }}</h3>
            <span class="badge bg-light text-dark border mb-3">{{ $medicine['category'] }}</span>
            <p class="mb-0">{{ $medicine['description'] }}</p>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Ketersediaan di apotek</h5>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Apotek</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicine['offers'] as $o)
                        <tr>
                            <td>
                                <strong>{{ $o['pharmacy'] }}</strong>
                                <div class="small text-muted">{{ $o['address'] }}</div>
                            </td>
                            <td>Rp {{ number_format($o['price'], 0, ',', '.') }}</td>
                            <td>
                                @if ($o['stock'] == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif ($o['stock'] <= 10)
                                    <span class="badge bg-warning text-dark">Terbatas ({{ $o['stock'] }})</span>
                                @else
                                    <span class="badge bg-success">Tersedia ({{ $o['stock'] }})</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('customer.medicines.request', $medicine['id']) }}" method="POST" class="mb-0">
                                    @csrf
                                    <input type="hidden" name="pharmacy_id" value="{{ $o['pharmacy_id'] }}">
                                    <button type="submit" class="btn btn-outline-success btn-sm">Minta Konfirmasi</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-muted small mt-3">
        Informasi stok dapat berubah sewaktu-waktu. Minta konfirmasi ke apotek sebelum berkunjung.
    </p>
@endsection
