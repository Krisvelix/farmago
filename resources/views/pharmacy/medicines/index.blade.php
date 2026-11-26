@extends('pharmacy.layouts.app')

@section('title', 'Data Obat')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="fg-page-head mb-0">
            <h1>Katalog Data Obat</h1>
            <p>Kelola seluruh daftar obat yang tersedia di {{ $pharmacy->pharmacy_name }}.</p>
        </div>
        <a href="{{ route('pharmacy.medicines.create') }}" class="fg-btn" style="width: auto;">
            <i class="bi bi-plus-lg"></i> Tambah Obat Baru
        </a>
    </div>

    {{-- Toolbar Pencarian & Filter Kategori --}}
    <div class="fg-card fg-card-pad mb-4">
        <form action="{{ route('pharmacy.medicines.index') }}" method="GET" class="d-flex flex-column gap-3">
            <div class="fg-search light">
                <i class="bi bi-search lead-ico"></i>
                <input type="search" name="q" placeholder="Cari nama atau deskripsi obat..." value="{{ $q }}" autocomplete="off">
                @if ($q !== '')
                    <a href="{{ route('pharmacy.medicines.index', ['category' => $category]) }}" class="clear show" title="Hapus pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
                <button type="submit" class="go">
                    <span>Cari</span><i class="bi bi-arrow-right"></i>
                </button>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="small fg-muted fw-bold me-1">Kategori:</span>
                <a href="{{ route('pharmacy.medicines.index', ['q' => $q]) }}"
                   class="fg-chip {{ $category === '' ? 'active' : '' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('pharmacy.medicines.index', ['q' => $q, 'category' => $cat]) }}"
                       class="fg-chip {{ $category === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Tabel Katalog Obat --}}
    @if ($medicines->isEmpty())
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-capsule"></i></div>
            <h3>Tidak ada obat ditemukan</h3>
            <p>Silakan sesuaikan kata kunci pencarian atau tambahkan obat baru ke apotek Anda.</p>
            @if ($q !== '' || $category !== '')
                <a href="{{ route('pharmacy.medicines.index') }}" class="fg-btn-sm outline">Reset Filter</a>
            @else
                <a href="{{ route('pharmacy.medicines.create') }}" class="fg-btn" style="width: auto;"><i class="bi bi-plus-lg"></i> Tambah Obat</a>
            @endif
        </div>
    @else
        <div class="fg-card" style="overflow: hidden;">
            <table class="fg-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Informasi Obat</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
                        <th>Ketersediaan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $index => $medicine)
                        <tr>
                            <td class="fg-muted fw-semibold">{{ $medicines->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('pharmacy.medicines.show', $medicine) }}" class="fw-bold text-dark text-decoration-none d-block">
                                    {{ $medicine->name }}
                                </a>
                                @if ($medicine->description)
                                    <div class="small fg-muted" style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $medicine->description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fg-tag neutral">{{ $medicine->category ?? 'Umum' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-family: var(--fg-display); font-size: 1.05rem; color: var(--fg-pine);">
                                    Rp {{ number_format($medicine->price, 0, ',', '.') }}
                                </span>
                                <span class="small fg-muted">/ {{ $medicine->unit }}</span>
                            </td>
                            <td>
                                @if ($medicine->stock <= 0)
                                    <span class="fg-pill out">Habis</span>
                                @elseif ($medicine->stock <= 10)
                                    <span class="fg-pill low">{{ $medicine->stock }} {{ $medicine->unit }}</span>
                                @else
                                    <span class="fg-pill ok">{{ $medicine->stock }} {{ $medicine->unit }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($medicine->is_available && $medicine->stock > 0)
                                    <span class="fg-tag success"><i class="bi bi-check2"></i> Aktif</span>
                                @else
                                    <span class="fg-tag neutral"><i class="bi bi-eye-slash"></i> Disembunyikan</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('pharmacy.medicines.show', $medicine) }}" class="fg-btn-sm outline" title="Lihat detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('pharmacy.medicines.edit', $medicine) }}" class="fg-btn-sm outline" title="Edit data">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('pharmacy.medicines.destroy', $medicine) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus obat {{ $medicine->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="fg-btn-sm danger" title="Hapus obat">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
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
