@extends('pharmacy.layouts.app')

@section('title', 'Permintaan Konfirmasi')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Daftar Permintaan Konfirmasi</h3>
    <p class="text-muted">Permintaan ketersediaan obat dari Customer untuk {{ $pharmacy->pharmacy_name }}.</p>
</div>

<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('pharmacy.requests', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('pharmacy.requests', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
        Menunggu ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('pharmacy.requests', ['status' => 'available']) }}" class="btn btn-sm {{ $status === 'available' ? 'btn-success' : 'btn-outline-success' }}">
        Tersedia ({{ $counts['available'] }})
    </a>
    <a href="{{ route('pharmacy.requests', ['status' => 'unavailable']) }}" class="btn btn-sm {{ $status === 'unavailable' ? 'btn-danger' : 'btn-outline-danger' }}">
        Tidak Tersedia ({{ $counts['unavailable'] }})
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Obat</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $req)
                    <tr>
                        <td>
                            <div>{{ $req->created_at->translatedFormat('d M Y') }}</div>
                            <small class="text-muted">{{ $req->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong>{{ $req->customer->name ?? '-' }}</strong>
                            <div class="small text-muted">{{ $req->customer->email ?? '' }}</div>
                        </td>
                        <td>
                            <strong>{{ $req->medicine->name ?? 'Obat Dihapus' }}</strong>
                            @if ($req->note)
                                <div class="small text-muted">"{{ $req->note }}"</div>
                            @endif
                        </td>
                        <td>{{ $req->quantity }} {{ $req->medicine->unit ?? '' }}</td>
                        <td>
                            @if ($req->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif ($req->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                            @if ($req->responded_at)
                                <div class="small text-muted">{{ $req->responded_at->translatedFormat('d M Y') }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($req->status === 'pending')
                                <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="available">
                                    <button type="submit" class="btn btn-success btn-sm">Tersedia</button>
                                </form>
                                <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="unavailable">
                                    <button type="submit" class="btn btn-danger btn-sm">Tidak Ada</button>
                                </form>
                            @else
                                <div class="small text-muted">
                                    {{ $req->response_note ?? 'Tanpa catatan.' }}
                                </div>
                                <button type="button" class="btn btn-link btn-sm p-0 text-success"
                                    data-bs-toggle="collapse" data-bs-target="#respondForm{{ $req->id }}">
                                    Ubah Respon
                                </button>
                                <div class="collapse mt-2" id="respondForm{{ $req->id }}">
                                    <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm mb-2">
                                            <option value="available" {{ $req->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="unavailable" {{ $req->status === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
                                        <textarea name="response_note" class="form-control form-control-sm mb-2" rows="2" placeholder="Catatan (opsional)">{{ $req->response_note }}</textarea>
                                        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada permintaan konfirmasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($requests->hasPages())
    <div class="mt-3">
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
