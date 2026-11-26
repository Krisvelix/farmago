@extends('pharmacy.layouts.app')

@section('title', 'Data Obat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Pengelolaan Data Obat</h3>
        <p class="text-muted mb-0">Kelola katalog obat di {{ $pharmacy->pharmacy_name }}.</p>
    </div>
    <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-success">Tambah Obat Baru</a>
</div>

<form action="{{ route('pharmacy.medicines.index') }}" method="GET" class="card border-0 shadow-sm p-3 mb-4">
    <div class="row g-2">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Cari nama atau deskripsi obat..." value="{{ $q }}">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-success">Filter</button>
            @if ($q !== '' || $category !== '')
                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $index => $medicine)
                    <tr>
                        <td>{{ $medicines->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $medicine->name }}</strong>
                            @if ($medicine->description)
                                <div class="small text-muted">{{ Str::limit($medicine->description, 60) }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $medicine->category ?? 'Umum' }}</span></td>
                        <td>Rp {{ number_format($medicine->price, 0, ',', '.') }}</td>
                        <td>
                            @if ($medicine->stock <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif ($medicine->stock <= 10)
                                <span class="badge bg-warning text-dark">{{ $medicine->stock }} {{ $medicine->unit }}</span>
                            @else
                                <span class="badge bg-success">{{ $medicine->stock }} {{ $medicine->unit }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($medicine->is_available && $medicine->stock > 0)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pharmacy.medicines.show', $medicine) }}" class="btn btn-primary btn-sm">Detail</a>
                            <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pharmacy.medicines.destroy', $medicine) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus obat {{ $medicine->name }}? Data tidak dapat dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data obat ditemukan.</td>
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
