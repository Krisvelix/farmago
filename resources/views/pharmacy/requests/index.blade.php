@extends('pharmacy.layouts.app')

@section('title', 'Permintaan Konfirmasi')

@section('content')
    <div class="fg-page-head">
        <h1>Permintaan Konfirmasi Customer</h1>
        <p>Pantau dan tanggapi pertanyaan ketersediaan obat yang masuk ke {{ $pharmacy->pharmacy_name }}.</p>
    </div>

    {{-- Status Filter Chips --}}
    <div class="fg-chips mb-4">
        <a href="{{ route('pharmacy.requests', ['status' => 'all']) }}"
           class="fg-chip {{ $status === 'all' ? 'active' : '' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('pharmacy.requests', ['status' => 'pending']) }}"
           class="fg-chip {{ $status === 'pending' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> Menunggu ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('pharmacy.requests', ['status' => 'available']) }}"
           class="fg-chip {{ $status === 'available' ? 'active' : '' }}">
            <i class="bi bi-check2-circle"></i> Tersedia ({{ $counts['available'] }})
        </a>
        <a href="{{ route('pharmacy.requests', ['status' => 'unavailable']) }}"
           class="fg-chip {{ $status === 'unavailable' ? 'active' : '' }}">
            <i class="bi bi-x-circle"></i> Tidak Tersedia ({{ $counts['unavailable'] }})
        </a>
    </div>

    @if ($requests->isEmpty())
        <div class="fg-card fg-empty">
            <div class="ico"><i class="bi bi-chat-dots"></i></div>
            <h3>Belum ada permintaan</h3>
            <p>Saat customer meminta konfirmasi stok obat di apotek Anda, daftarnya akan muncul di sini.</p>
        </div>
    @else
        {{-- Tampilan Desktop (Tabel) --}}
        <div class="fg-card d-none d-lg-block" style="overflow: hidden;">
            <table class="fg-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Waktu</th>
                        <th>Customer</th>
                        <th>Obat & Catatan</th>
                        <th>Jumlah</th>
                        <th>Status Respon</th>
                        <th class="text-end" style="min-width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $req)
                        <tr>
                            <td>
                                <div>{{ $req->created_at->translatedFormat('d M Y') }}</div>
                                <small class="fg-muted">{{ $req->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong class="d-block">{{ $req->customer->name ?? '-' }}</strong>
                                <span class="small fg-muted">{{ $req->customer->email ?? '' }}</span>
                            </td>
                            <td>
                                <strong class="d-block">{{ $req->medicine->name ?? 'Obat Dihapus' }}</strong>
                                @if ($req->note)
                                    <div class="small fg-muted fst-italic">"{{ $req->note }}"</div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $req->quantity }} {{ $req->medicine->unit ?? '' }}</td>
                            <td>
                                @if ($req->status === 'pending')
                                    <span class="fg-pill low">Menunggu</span>
                                @elseif ($req->status === 'available')
                                    <span class="fg-pill ok">Tersedia</span>
                                @else
                                    <span class="fg-pill out">Tidak Ada</span>
                                @endif

                                @if ($req->response_note)
                                    <div class="small fg-muted mt-1" style="max-width: 200px;">
                                        <em>{{ $req->response_note }}</em>
                                    </div>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($req->status === 'pending')
                                    <div class="d-inline-flex gap-1">
                                        <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="available">
                                            <button type="submit" class="fg-btn-sm success">
                                                <i class="bi bi-check2"></i> Ada
                                            </button>
                                        </form>
                                        <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="unavailable">
                                            <button type="submit" class="fg-btn-sm danger">
                                                <i class="bi bi-x-lg"></i> Kosong
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <div class="mt-1">
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold"
                                            style="color: var(--fg-green-d); font-size: .8rem;"
                                            data-bs-toggle="collapse" data-bs-target="#respondForm{{ $req->id }}">
                                        {{ $req->status === 'pending' ? '+ Beri Catatan' : 'Ubah Respon' }}
                                    </button>
                                </div>

                                <div class="collapse mt-2 text-start" id="respondForm{{ $req->id }}">
                                    <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST"
                                          class="p-2 border rounded" style="background: var(--fg-mint-2); border-color: var(--fg-line) !important;">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm mb-2">
                                            <option value="available" {{ $req->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="unavailable" {{ $req->status === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
                                        <textarea name="response_note" class="form-control form-control-sm mb-2" rows="2"
                                                  placeholder="Catatan tambahan untuk customer...">{{ $req->response_note }}</textarea>
                                        <button type="submit" class="fg-btn-sm success w-100 justify-content-center">Kirim Respon</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tampilan Mobile (List) --}}
        <div class="fg-card d-lg-none">
            <ul class="fg-list">
                @foreach ($requests as $req)
                    <li>
                        <span class="ico {{ $req->status === 'unavailable' ? 'out' : ($req->status === 'pending' ? 'low' : '') }}">
                            <i class="bi {{ $req->status === 'available' ? 'bi-check2-circle' : ($req->status === 'unavailable' ? 'bi-x-circle' : 'bi-hourglass-split') }}"></i>
                        </span>
                        <div class="body">
                            <strong>{{ $req->medicine->name ?? 'Obat Dihapus' }} ({{ $req->quantity }} {{ $req->medicine->unit ?? '' }})</strong>
                            <span>Customer: {{ $req->customer->name ?? '-' }} &middot; {{ $req->created_at->translatedFormat('d M H:i') }}</span>
                            @if ($req->note)
                                <span class="d-block mt-1 fst-italic">"{{ $req->note }}"</span>
                            @endif
                            @if ($req->response_note)
                                <span class="d-block text-success mt-1">Respon: {{ $req->response_note }}</span>
                            @endif

                            <div class="mt-2 d-flex gap-2">
                                @if ($req->status === 'pending')
                                    <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" class="fg-btn-sm success">Ada</button>
                                    </form>
                                    <form action="{{ route('pharmacy.requests.respond', $req) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="unavailable">
                                        <button type="submit" class="fg-btn-sm danger">Kosong</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($req->status === 'pending')
                                <span class="fg-pill low">Menunggu</span>
                            @elseif ($req->status === 'available')
                                <span class="fg-pill ok">Tersedia</span>
                            @else
                                <span class="fg-pill out">Tidak Ada</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if ($requests->hasPages())
            <div class="mt-3">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
@endsection
