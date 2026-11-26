@extends('customer.layouts.app')

@section('title', 'Cari Obat')

@section('content')
    @php
        $suggestions = ['Paracetamol', 'Amoxicillin', 'Cetirizine', 'Antasida', 'Vitamin C'];
        $categories = $medicines->pluck('category')->unique()->values();
    @endphp

    <div class="fg-page-head">
        <h1>Cari obat</h1>
        <p>Lihat harga dan stok di apotek terdekat sebelum kamu berangkat.</p>
    </div>

    {{-- Kolom pencarian --}}
    <form action="{{ route('customer.medicines') }}" method="GET" role="search" id="searchForm">
        <div class="fg-search light">
            <i class="bi bi-search lead-ico"></i>
            <input type="search" name="q" id="searchInput" value="{{ $q }}"
                   placeholder="Nama obat, contoh: Paracetamol"
                   autocomplete="off" enterkeyhint="search" aria-label="Cari nama obat">
            <button type="button" class="clear {{ $q !== '' ? 'show' : '' }}" id="clearBtn" aria-label="Hapus kata kunci">
                <i class="bi bi-x-lg"></i>
            </button>
            <button type="submit" class="go"><i class="bi bi-search"></i><span>Cari</span></button>
        </div>
    </form>

    @if ($q === '')
        <div class="fg-chips mt-3">
            <span class="small fw-semibold fg-muted align-self-center me-1">Sering dicari:</span>
            @foreach ($suggestions as $s)
                <a class="fg-chip" href="{{ route('customer.medicines', ['q' => $s]) }}">{{ $s }}</a>
            @endforeach
        </div>
    @endif

    @if ($medicines->isNotEmpty())
        {{-- Filter kategori --}}
        @if ($categories->count() > 1)
            <div class="fg-cats mt-4" id="catChips" role="group" aria-label="Filter kategori">
                <button type="button" class="fg-chip active" data-cat="">Semua</button>
                @foreach ($categories as $c)
                    <button type="button" class="fg-chip" data-cat="{{ $c }}">{{ $c }}</button>
                @endforeach
            </div>
        @endif

        {{-- Toolbar hasil --}}
        <div class="fg-toolbar">
            <div class="count" aria-live="polite">
                <span id="resultCount">{{ $medicines->count() }}</span> obat
                @if ($q !== '')
                    <small>untuk &ldquo;{{ $q }}&rdquo;</small>
                @endif
            </div>

            <label class="fg-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="onlyAvail">
                Hanya yang ada stok
            </label>

            <select class="fg-select" id="sortBy" aria-label="Urutkan">
                <option value="default">Urutan awal</option>
                <option value="price">Harga termurah</option>
                <option value="name">Nama A&ndash;Z</option>
                <option value="pharm">Apotek terbanyak</option>
            </select>
        </div>

        <div class="fg-grid" id="medGrid">
            @foreach ($medicines as $m)
                @include('customer.medicines._card', ['m' => $m, 'q' => $q])
            @endforeach
        </div>

        {{-- Muncul kalau filter di atas tidak menyisakan hasil --}}
        <div class="fg-card fg-empty mt-3" id="filterEmpty" hidden>
            <div class="ico"><i class="bi bi-funnel"></i></div>
            <h3>Tidak ada obat dengan filter ini</h3>
            <p>Coba matikan filter stok atau pilih kategori lain.</p>
            <button type="button" class="fg-btn ghost" id="resetFilter">Atur ulang filter</button>
        </div>
    @else
        <div class="fg-card fg-empty mt-4">
            <div class="ico"><i class="bi bi-search"></i></div>
            <h3>&ldquo;{{ $q }}&rdquo; belum ketemu</h3>
            <p>Cek ejaan atau coba nama lain, misalnya nama zat aktifnya.</p>
            <div class="fg-chips mb-3">
                @foreach ($suggestions as $s)
                    <a class="fg-chip" href="{{ route('customer.medicines', ['q' => $s]) }}">{{ $s }}</a>
                @endforeach
            </div>
            <a href="{{ route('customer.medicines') }}" class="fg-btn">Lihat semua obat</a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
(function () {
    // --- Kolom pencarian ---
    const input = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');
    const form = document.getElementById('searchForm');

    input.addEventListener('input', () => clearBtn.classList.toggle('show', input.value.length > 0));
    clearBtn.addEventListener('click', () => {
        const hadQuery = {{ $q !== '' ? 'true' : 'false' }};
        input.value = '';
        clearBtn.classList.remove('show');
        if (hadQuery) { form.submit(); } else { input.focus(); }
    });
    // fokus otomatis hanya di desktop supaya keyboard HP tidak langsung muncul
    if (window.matchMedia('(pointer: fine)').matches && !input.value) input.focus();

    // --- Filter & urutan di hasil ---
    const grid = document.getElementById('medGrid');
    if (!grid) return;

    const cards = Array.from(grid.children);
    cards.forEach((c, i) => c.dataset.i = i);

    const state = { cat: '', avail: false, sort: 'default' };
    const countEl = document.getElementById('resultCount');
    const emptyEl = document.getElementById('filterEmpty');
    const availEl = document.getElementById('onlyAvail');
    const sortEl = document.getElementById('sortBy');
    const chips = document.getElementById('catChips');

    function apply() {
        let list = cards.filter(c =>
            (!state.cat || c.dataset.cat === state.cat) &&
            (!state.avail || c.dataset.avail === '1')
        );

        const by = {
            default: (a, b) => a.dataset.i - b.dataset.i,
            price:   (a, b) => a.dataset.price - b.dataset.price,
            name:    (a, b) => a.dataset.name.localeCompare(b.dataset.name, 'id'),
            pharm:   (a, b) => b.dataset.pharm - a.dataset.pharm,
        };
        list.sort(by[state.sort]);

        cards.forEach(c => c.hidden = true);
        list.forEach(c => { c.hidden = false; grid.appendChild(c); });

        countEl.textContent = list.length;
        emptyEl.hidden = list.length > 0;
    }

    if (chips) chips.addEventListener('click', e => {
        const b = e.target.closest('.fg-chip'); if (!b) return;
        chips.querySelectorAll('.fg-chip').forEach(x => x.classList.toggle('active', x === b));
        state.cat = b.dataset.cat; apply();
    });
    availEl.addEventListener('change', () => { state.avail = availEl.checked; apply(); });
    sortEl.addEventListener('change', () => { state.sort = sortEl.value; apply(); });

    document.getElementById('resetFilter').addEventListener('click', () => {
        state.cat = ''; state.avail = false; state.sort = 'default';
        availEl.checked = false; sortEl.value = 'default';
        if (chips) chips.querySelectorAll('.fg-chip').forEach((x, i) => x.classList.toggle('active', i === 0));
        apply();
    });
})();
</script>
@endpush
