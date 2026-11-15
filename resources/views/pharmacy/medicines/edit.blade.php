@extends('pharmacy.layouts.app')

@section('title', 'Edit Data Obat')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Edit Data Obat</h3>
    <p class="text-muted">Perbarui informasi obat {{ $medicine->name }}.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm p-4" style="max-width: 700px;">
    <form action="{{ route('pharmacy.medicines.update', $medicine) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Obat</label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', $medicine->name) }}" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Kategori Obat</label>
                <input type="text" name="category" class="form-control"
                    value="{{ old('category', $medicine->category) }}" list="categoryList">
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
                <label class="form-label">Satuan Kemasan</label>
                <select name="unit" class="form-select" required>
                    <option value="Tablet" {{ old('unit', $medicine->unit) === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Kapsul" {{ old('unit', $medicine->unit) === 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                    <option value="Strip" {{ old('unit', $medicine->unit) === 'Strip' ? 'selected' : '' }}>Strip</option>
                    <option value="Botol" {{ old('unit', $medicine->unit) === 'Botol' ? 'selected' : '' }}>Botol</option>
                    <option value="Tube" {{ old('unit', $medicine->unit) === 'Tube' ? 'selected' : '' }}>Tube</option>
                    <option value="Box" {{ old('unit', $medicine->unit) === 'Box' ? 'selected' : '' }}>Box</option>
                    <option value="Sachet" {{ old('unit', $medicine->unit) === 'Sachet' ? 'selected' : '' }}>Sachet</option>
                    <option value="Pcs" {{ old('unit', $medicine->unit) === 'Pcs' ? 'selected' : '' }}>Pcs</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Harga Satuan (Rp)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="price" class="form-control"
                        value="{{ old('price', $medicine->price) }}" min="0" step="100" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stock" class="form-control"
                    value="{{ old('stock', $medicine->stock) }}" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi Obat</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $medicine->description) }}</textarea>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                    {{ old('is_available', $medicine->is_available) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_available">Tampilkan sebagai Tersedia untuk Customer</label>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
