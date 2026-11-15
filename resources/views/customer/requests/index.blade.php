@extends('customer.layouts.app')

@section('title', 'Riwayat Permintaan Konfirmasi')

@section('content')
    <h3 class="fw-bold mb-3">Riwayat Permintaan Konfirmasi</h3>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Obat</th>
                        <th>Apotek</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $r)
                        <tr>
                            <td>{{ $r['date'] }}</td>
                            <td>{{ $r['medicine'] }}</td>
                            <td>{{ $r['pharmacy'] }}</td>
                            <td>@include('customer.requests._status', ['status' => $r['status']])</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">Belum ada permintaan konfirmasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
