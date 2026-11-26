@extends('pharmacy.layouts.app')

@section('title', 'Tambah Obat Baru')

@section('content')
    <nav class="fg-crumb" aria-label="Breadcrumb">
        <a href="{{ route('pharmacy.medicines.index') }}"><i class="bi bi-arrow-left"></i> Data Obat</a>
        <span>/</span>
        <span>Tambah Obat Baru</span>
    </nav>

    <div class="fg-page-head">
        <h1>Tambah Obat Baru</h1>
        <p>Lengkapi formulir di bawah ini untuk menambahkan produk obat ke katalog {{ $pharmacy->pharmacy_name }}.</p>
    </div>

    @if ($errors->any())
        <div class="fg-flash" style="background: var(--fg-red-bg); color: var(--fg-red); border-color: #f6c8c2;" role="alert">
            <i class="bi bi-exclamation-octagon-fill text-danger"></i>
            <div>
                <strong class="d-block mb-1">Terdapat kesalahan pengisian data:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="fg-card fg-card-pad" style="max-width: 760px;">
        <form action="{{ route('pharmacy.medicines.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="fg-form-label">Nama Obat <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" placeholder="Contoh: Paracetamol 500 mg" required>
                <div class="fg-form-hint">Sertakan dosis atau takaran agar jelas bagi customer.</div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="fg-form-label">Kategori Obat</label>
                    <input type="text" name="category" class="form-control"
                           value="{{ old('category') }}" placeholder="Pilih atau ketik kategori..."
                           list="categoryList">
                    <datalist id="categoryList">
                        <option value="Pereda Demam & Nyeri">
                        <option value="Antibiotik">
                        <option value="Antihistamin & Alergi">
                        <option value="Obat Maag & Lambung">
                        <option value="Vitamin & Suplemen">
                        <option value="Obat Batuk & Flu">
                        <option value="Obat Luar & Salep">
                        <option value="Mata & THT">
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label class="fg-form-label">Satuan Kemasan <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select" required>
                        <option value="Strip" {{ old('unit') === 'Strip' ? 'selected' : '' }}>Strip</option>
                        <option value="Tablet" {{ old('unit') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Kapsul" {{ old('unit') === 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="Botol" {{ old('unit') === 'Botol' ? 'selected' : '' }}>Botol</option>
                        <option value="Tube" {{ old('unit') === 'Tube' ? 'selected' : '' }}>Tube</option>
                        <option value="Box" {{ old('unit') === 'Box' ? 'selected' : '' }}>Box</option>
                        <option value="Sachet" {{ old('unit') === 'Sachet' ? 'selected' : '' }}>Sachet</option>
                        <option value="Pcs" {{ old('unit') === 'Pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="fg-form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price" class="form-control"
                               value="{{ old('price', 0) }}" min="0" step="100" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fg-form-label">Jumlah Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', 0) }}" min="0" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="fg-form-label">Deskripsi Obat</label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Jelaskan kegunaan, indikasi, atau cara penggunaan obat...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <div class="form-check fg-switch">
                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                           {{ old('is_available', true) ? 'checked' : '' }}>
                    <label class="form-check-label ms-2 fw-semibold" for="is_available">
                        Tampilkan sebagai Tersedia di pencarian Customer
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2 pt-2 border-top">
                <button type="submit" class="fg-btn" style="width: auto;">
                    <i class="bi bi-check-lg"></i> Simpan Data Obat
                </button>
                <a href="{{ route('pharmacy.medicines.index') }}" class="fg-btn ghost" style="width: auto;">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
