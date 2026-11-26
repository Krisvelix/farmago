{{--
    Kartu obat (dipakai di dashboard & halaman cari obat)
    Variabel: $m (data obat), $q (opsional, untuk highlight kata kunci)
--}}
@php
    $q = $q ?? '';

    // Ikon sesuai kategori
    $cat = strtolower($m['category']);
    $icon = match (true) {
        str_contains($cat, 'demam') || str_contains($cat, 'nyeri') => 'bi-thermometer-half',
        str_contains($cat, 'antibiotik')                            => 'bi-shield-plus',
        str_contains($cat, 'antihistamin') || str_contains($cat, 'alergi') => 'bi-flower1',
        str_contains($cat, 'maag')                                  => 'bi-droplet-half',
        str_contains($cat, 'suplemen') || str_contains($cat, 'vitamin') => 'bi-brightness-high',
        default                                                     => 'bi-capsule-pill',
    };

    // Ringkasan stok dari semua apotek
    $offers = collect($m['offers']);
    $totalStock = $offers->sum('stock');
    $availableAt = $offers->where('stock', '>', 0)->count();

    if ($availableAt === 0) {
        [$stockClass, $stockText] = ['out', 'Stok habis'];
    } elseif ($totalStock <= 10) {
        [$stockClass, $stockText] = ['low', 'Stok terbatas'];
    } else {
        [$stockClass, $stockText] = ['ok', 'Stok tersedia'];
    }

    // Highlight kata kunci pada nama obat
    $name = e($m['name']);
    if ($q !== '') {
        $name = preg_replace('/(' . preg_quote(e($q), '/') . ')/i', '<mark>$1</mark>', $name);
    }
@endphp

<a href="{{ route('customer.medicines.show', $m['id']) }}"
   class="fg-med"
   data-name="{{ strtolower($m['name']) }}"
   data-cat="{{ $m['category'] }}"
   data-price="{{ $m['min_price'] }}"
   data-pharm="{{ $availableAt }}"
   data-avail="{{ $availableAt > 0 ? 1 : 0 }}">
    <div class="top">
        <span class="ico"><i class="bi {{ $icon }}"></i></span>
        <div>
            <h3>{!! $name !!}</h3>
            <div class="cat">{{ $m['category'] }}</div>
        </div>
    </div>

    <p class="desc">{{ $m['description'] }}</p>

    <div class="mb-3"><span class="fg-pill {{ $stockClass }}">{{ $stockText }}</span></div>

    <div class="foot">
        <div>
            <div class="price-label">Harga mulai</div>
            <div class="price">Rp {{ number_format($m['min_price'], 0, ',', '.') }}</div>
        </div>
        <div class="where"><i class="bi bi-shop"></i> {{ $m['pharmacy_total'] }} apotek</div>
    </div>
</a>
