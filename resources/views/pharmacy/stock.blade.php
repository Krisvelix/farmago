@extends('pharmacy.layouts.app')

@section('title', 'Kelola Stok dan Harga')

@section('content')
    <div class="fg-page-head">
        <h1>Pengelolaan Stok dan Harga</h1>
        <p>Perbarui harga dan jumlah stok obat secara instan tanpa perlu masuk ke form edit.</p>
    </div>

    {{-- Filter & Pencarian Toolbar --}}
    <div class="fg-card fg-card-pad mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <form action="{{ route('pharmacy.stock') }}" method="GET" class="d-flex gap-2" style="max-width: 360px; width: 100%;">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari nama atau kategori..." value="{{ $q }}">
                    <button type="submit" class="fg-btn-sm success" style="border-radius: 0 8px 8px 0;"><i class="bi bi-search"></i></button>
                </div>
                @if ($q !== '')
                    <a href="{{ route('pharmacy.stock', ['filter' => $filter]) }}" class="fg-btn-sm outline" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            <div class="fg-chips">
                <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'all']) }}"
                   class="fg-chip {{ $filter === 'all' ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'available']) }}"
                   class="fg-chip {{ $filter === 'available' ? 'active' : '' }}">
                    Tersedia
                </a>
                <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'low_stock']) }}"
                   class="fg-chip {{ $filter === 'low_stock' ? 'active' : '' }}">
                    Menipis (&le; 10)
                </a>
                <a href="{{ route('pharmacy.stock', ['q' => $q, 'filter' => 'out_of_stock']) }}"
                   class="fg-chip {{ $filter === 'out_of_stock' ? 'active' : '' }}">
                    Habis (0)
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Inline Update --}}
    @if ($medicines->isEmpty())
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-sliders"></i></div>
            <h3>Tidak ada obat yang sesuai</h3>
            <p>Cobalah mengganti filter atau cari kata kunci lain.</p>
            <a href="{{ route('pharmacy.stock') }}" class="fg-btn-sm outline">Reset Filter</a>
        </div>
    @else
        <div class="fg-card" style="overflow: hidden;">
            <table class="fg-table">
                <thead>
                    <tr>
                        <th style="min-width: 220px;">Nama Obat</th>
                        <th>Kategori</th>
                        <th style="min-width: 170px;">Harga Jual (Rp)</th>
                        <th style="min-width: 150px;">Stok</th>
                        <th style="text-align: center;">Tersedia</th>
                        <th class="text-end" style="min-width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $med)
                        <tr>
                            <td>
                                <strong class="d-block">{{ $med->name }}</strong>
                                <span class="small fg-muted">Satuan: {{ $med->unit }}</span>
                            </td>
                            <td>
                                <span class="fg-tag neutral">{{ $med->category ?? 'Umum' }}</span>
                            </td>
                            <form action="{{ route('pharmacy.stock.update', $med) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" class="form-control" value="{{ $med->price }}" min="0" step="100" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="stock" class="form-control" value="{{ $med->stock }}" min="0" required>
                                        <span class="input-group-text">{{ $med->unit }}</span>
                                    </div>
                                    @if ($med->stock <= 0)
                                        <span class="fg-pill out mt-1">Habis</span>
                                    @elseif ($med->stock <= 10)
                                        <span class="fg-pill low mt-1">Menipis</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ $med->is_available ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button type="submit" class="fg-btn-sm success">
                                        <i class="bi bi-save"></i> Simpan
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($medicines->hasPages())
            <div class="mt-3">
                {{ $medicines->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
@endsection
