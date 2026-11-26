@extends('customer.layouts.app')

@section('title', $medicine['name'])

@section('content')
    @php
        $offers = collect($medicine['offers']);
        $inStock = $offers->where('stock', '>', 0);
        $cheapest = $inStock->sortBy('price')->first();   // penawaran termurah yang masih ada stok

        // Urutkan: yang ada stok dulu (termurah di atas), yang habis di bawah
        $sorted = $inStock->sortBy('price')->concat($offers->where('stock', '<=', 0))->values();

        $cat = strtolower($medicine['category']);
        $icon = match (true) {
            str_contains($cat, 'demam') || str_contains($cat, 'nyeri') => 'bi-thermometer-half',
            str_contains($cat, 'antibiotik')                            => 'bi-shield-plus',
            str_contains($cat, 'antihistamin')                          => 'bi-flower1',
            str_contains($cat, 'maag')                                  => 'bi-droplet-half',
            str_contains($cat, 'suplemen')                              => 'bi-brightness-high',
            default                                                     => 'bi-capsule-pill',
        };
    @endphp

    <nav class="fg-crumb" aria-label="Breadcrumb">
        <a href="{{ route('customer.medicines') }}"><i class="bi bi-arrow-left"></i> Cari obat</a>
        <span>/</span>
        <span>{{ $medicine['name'] }}</span>
    </nav>

    {{-- Info obat --}}
    <div class="fg-card fg-detail-head">
        <div>
            <div class="ico"><i class="bi {{ $icon }}"></i></div>
            <span class="cat">{{ $medicine['category'] }}</span>
            <h1>{{ $medicine['name'] }}</h1>
            <p>{{ $medicine['description'] }}</p>
        </div>

        <div class="fg-pricebox">
            @if ($cheapest)
                <div class="l">Harga termurah</div>
                <div class="v">Rp {{ number_format($cheapest['price'], 0, ',', '.') }}</div>
                <div class="s">di {{ $cheapest['pharmacy'] }}</div>
            @else
                <div class="l">Ketersediaan</div>
                <div class="v">Habis</div>
                <div class="s">Belum ada apotek dengan stok</div>
            @endif
        </div>
    </div>

    {{-- Daftar apotek --}}
    <div class="fg-section-head">
        <h2 class="fg-section-title">Tersedia di {{ $inStock->count() }} dari {{ $offers->count() }} apotek</h2>
    </div>

    <div class="fg-offers">
        @foreach ($sorted as $o)
            @php
                $isBest = $cheapest && $o['pharmacy_id'] === $cheapest['pharmacy_id'] && $inStock->count() > 1;
                if ($o['stock'] == 0)       { $cls = 'out'; $txt = 'Habis'; }
                elseif ($o['stock'] <= 10)  { $cls = 'low'; $txt = 'Sisa ' . $o['stock']; }
                else                        { $cls = 'ok';  $txt = 'Tersedia'; }

                // apakah form apotek ini yang barusan disubmit (untuk menampilkan error & old input)
                $isSubmittedPharmacy = (string) old('pharmacy_id') === (string) $o['pharmacy_id'];
            @endphp

            <div class="fg-offer {{ $isBest ? 'best' : '' }} {{ $o['stock'] == 0 ? 'empty' : '' }}">
                <div>
                    <div class="name">
                        {{ $o['pharmacy'] }}
                        @if ($isBest) <span class="tag-best">Termurah</span> @endif
                    </div>
                    <div class="addr"><i class="bi bi-geo-alt"></i> {{ $o['address'] }}</div>
                </div>

                <div class="meta">
                    <div class="k">Harga</div>
                    <div class="money">Rp {{ number_format($o['price'], 0, ',', '.') }}</div>
                </div>

                <div class="meta">
                    <div class="k">Stok</div>
                    <span class="fg-pill {{ $cls }}">{{ $txt }}</span>
                </div>

                <form action="{{ route('customer.medicines.request', $medicine['id']) }}" method="POST" class="mb-0 js-request">
                    @csrf
                    <input type="hidden" name="pharmacy_id" value="{{ $o['pharmacy_id'] }}">

                    @if ($o['stock'] == 0)
                        <button type="button" class="fg-btn" disabled>
                            <i class="bi bi-x-circle"></i> Stok habis
                        </button>
                    @else
                        @if ($isSubmittedPharmacy && session('error'))
                            <div class="alert alert-danger small text-start py-2 mb-2" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($isSubmittedPharmacy && $errors->any())
                            <div class="alert alert-danger small text-start py-2 mb-2" role="alert">
                                Terjadi kesalahan pada data permintaan.
                            </div>
                        @endif

                        <div class="text-start mb-2">
                            <label for="quantity-{{ $o['pharmacy_id'] }}" class="form-label small mb-1">Jumlah</label>
                            <input type="number"
                                   id="quantity-{{ $o['pharmacy_id'] }}"
                                   name="quantity"
                                   min="1"
                                   value="{{ $isSubmittedPharmacy ? old('quantity', 1) : 1 }}"
                                   class="form-control form-control-sm">
                            @if ($isSubmittedPharmacy)
                                @error('quantity')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <div class="text-start mb-2">
                            <label for="note-{{ $o['pharmacy_id'] }}" class="form-label small mb-1">Catatan (opsional)</label>
                            <input type="text"
                                   id="note-{{ $o['pharmacy_id'] }}"
                                   name="note"
                                   maxlength="255"
                                   value="{{ $isSubmittedPharmacy ? old('note') : '' }}"
                                   class="form-control form-control-sm">
                            @if ($isSubmittedPharmacy)
                                @error('note')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <button type="submit" class="{{ $isBest ? 'fg-btn' : 'fg-btn ghost' }}">
                            <i class="bi bi-chat-dots"></i> Minta konfirmasi
                        </button>
                    @endif
                </form>
            </div>
        @endforeach
    </div>

    <div class="fg-note">
        <i class="bi bi-info-circle-fill"></i>
        <div>Stok bisa berubah kapan saja. Tekan <strong>Minta konfirmasi</strong> dan apotek akan memastikan obatnya masih ada sebelum kamu datang.</div>
    </div>
@endsection

@push('scripts')
<script>
    // cegah klik ganda saat permintaan dikirim
    document.querySelectorAll('.js-request').forEach(f => f.addEventListener('submit', () => {
        const b = f.querySelector('button[type=submit]');
        if (!b) return;
        b.classList.add('loading');
        b.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';
    }));
</script>
@endpush