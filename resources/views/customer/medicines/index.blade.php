@extends('customer.layouts.app')

@section('title', 'Cari Obat')

@section('content')
    <h3 class="fw-bold mb-3">Cari Obat</h3>

    <form action="{{ route('customer.medicines') }}" method="GET" class="card border-0 shadow-sm p-3 mb-4">
        <div class="input-group">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Ketik nama obat, contoh: Paracetamol">
            <button class="btn btn-success" type="submit">Cari</button>
            @if ($q !== '')
                <a href="{{ route('customer.medicines') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <p class="text-muted">
        @if ($q !== '')
            Menampilkan {{ $medicines->count() }} hasil untuk "<strong>{{ $q }}</strong>"
        @else
            Menampilkan semua obat ({{ $medicines->count() }})
        @endif
    </p>

    <div class="row">
        @forelse ($medicines as $m)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold">{{ $m['name'] }}</h5>
                        <span class="badge bg-light text-dark border align-self-start mb-2">{{ $m['category'] }}</span>
                        <p class="text-muted small">{{ \Illuminate\Support\Str::limit($m['description'], 90) }}</p>
                        <div class="mt-auto">
                            <p class="mb-1">Tersedia di <strong>{{ $m['pharmacy_total'] }}</strong> apotek</p>
                            <p class="mb-3">Mulai <strong class="text-success">Rp {{ number_format($m['min_price'], 0, ',', '.') }}</strong></p>
                            <a href="{{ route('customer.medicines.show', $m['id']) }}" class="btn btn-success w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center py-5">
                    Obat yang kamu cari belum ditemukan. Coba kata kunci lain.
                </div>
            </div>
        @endforelse
    </div>
@endsection
