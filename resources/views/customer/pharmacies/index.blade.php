@extends('customer.layouts.app')

@section('title', 'Daftar Apotek')

@section('content')
    <h3 class="fw-bold mb-3">Daftar Apotek</h3>
    <p class="text-muted">Apotek yang sudah terverifikasi oleh Admin Farmago.</p>

    <div class="row">
        @foreach ($pharmacies as $p)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{ $p['name'] }}</h5>
                            <span class="badge bg-success">Terverifikasi</span>
                        </div>
                        <p class="text-muted mb-1">{{ $p['address'] }}</p>
                        <p class="mb-3">Telp: {{ $p['phone'] }}</p>
                        <p class="small text-muted mb-0">{{ $p['medicine_count'] }} jenis obat terdaftar</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
