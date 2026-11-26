@extends('customer.layouts.app')

@section('title', 'Daftar Apotek')

@section('content')
    <div class="fg-page-head">
        <h1>Apotek</h1>
        <p>Semua apotek di sini sudah diverifikasi oleh admin Farmago.</p>
    </div>

    <div class="fg-grid">
        @foreach ($pharmacies as $p)
            <article class="fg-card fg-pharm">
                <div class="head">
                    <span class="ico"><i class="bi bi-shop"></i></span>
                    <div>
                        <h3>{{ $p['name'] }}</h3>
                        <span class="fg-verified"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                    </div>
                </div>

                <div class="row-i"><i class="bi bi-geo-alt-fill"></i><span>{{ $p['address'] }}</span></div>
                <div class="row-i"><i class="bi bi-telephone-fill"></i><span>{{ $p['phone'] }}</span></div>

                <div class="foot">
                    <span class="fg-muted">{{ $p['medicine_count'] }} jenis obat</span>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $p['phone']) }}">
                        <i class="bi bi-telephone"></i> Telepon
                    </a>
                </div>
            </article>
        @endforeach
    </div>
@endsection
