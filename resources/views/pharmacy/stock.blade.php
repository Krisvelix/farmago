@extends('pharmacy.layouts.app')

@section('title', 'Kelola Stok dan Harga')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Pengelolaan Stok dan Harga</h3>
    <p class="text-muted">Perbarui stok dan harga obat secara cepat di {{ $pharmacy->pharmacy_name }}.</p>
</div>

<div class="card border-0 shadow-sm p-3 mb-4">
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <form action="{{ route('pharmacy.stock') }}" method="GET" class="d-flex gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="q" class="form-control" placeholder="Cari obat..." value="{{ $q }}" style="max-width: 280px;">
            <button type="submit" class="btn btn-success">Cari</button>
            @if ($q !== '')
                <a href="{{ route('pharmacy.stock', ['filter' => $filter]) }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>

        <div class="d-flex gap-2 ms-auto">
            <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'all']) }}" class="btn btn-sm {{ $filter === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">Semua</a>
            <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'available']) }}" class="btn btn-sm {{ $filter === 'available' ? 'btn-success' : 'btn-outline-success' }}">Tersedia</a>
            <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'low_stock']) }}" class="btn btn-sm {{ $filter === 'low_stock' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">Menipis</a>
            <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'out_of_stock']) }}" class="btn btn-sm {{ $filter === 'out_of_stock' ? 'btn-danger' : 'btn-outline-danger' }}">Habis</a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Harga (Rp)</th>
                    <th>Stok</th>
                    <th>Tersedia</th>
                    <th class="text-center">Simpan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $med)
                    <tr>
                        <td>
                            <strong>{{ $med->name }}</strong>
                            <div class="small text-muted">Satuan: {{ $med->unit }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $med->category ?? 'Umum' }}</span></td>
                        <form action="{{ route('pharmacy.stock.update', $med) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 160px;">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control" value="{{ $med->price }}" min="0" step="100" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 130px;">
                                    <input type="number" name="stock" class="form-control" value="{{ $med->stock }}" min="0" required>
                                    <span class="input-group-text">{{ $med->unit }}</span>
                                </div>
                                @if ($med->stock <= 0)
                                    <span class="badge bg-danger mt-1 d-block" style="width: fit-content;">Habis</span>
                                @elseif ($med->stock <= 10)
                                    <span class="badge bg-warning text-dark mt-1 d-block" style="width: fit-content;">Menipis</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ $med->is_available ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada obat ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($medicines->hasPages())
    <div class="mt-3">
        {{ $medicines->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
