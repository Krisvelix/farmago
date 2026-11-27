@extends('admin.layouts.app')

@section('title', 'Verifikasi Apotek')

@section('content')
    <div class="fg-page-head"><h1>Verifikasi Apotek</h1><p>Periksa dan kelola pendaftaran apotek Farmago.</p></div>

    <div class="fg-card" style="overflow: hidden;">
        <div class="table-responsive">
            <table class="fg-table">
                <thead><tr><th>No.</th><th>Apotek</th><th>Pemilik</th><th>Kontak</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($pharmacies as $pharmacy)
                        <tr>
                            <td class="fg-muted">{{ $pharmacies->firstItem() + $loop->index }}</td>
                            <td><div class="d-flex align-items-center gap-2"><span class="fg-avatar" style="background: var(--fg-pine);"><i class="bi bi-shop"></i></span><strong>{{ $pharmacy->pharmacy_name }}</strong></div></td>
                            <td>{{ $pharmacy->owner_name }}<div class="small fg-muted">{{ $pharmacy->user->email }}</div></td>
                            <td class="fg-muted">{{ $pharmacy->phone }}</td>
                            <td>
                                @if ($pharmacy->verification_status === 'approved') <span class="fg-pill ok">Disetujui</span>
                                @elseif ($pharmacy->verification_status === 'rejected') <span class="fg-pill out">Ditolak</span>
                                @else <span class="fg-pill wait">Menunggu</span> @endif
                            </td>
                            <td class="text-end"><a href="{{ url('/admin/pharmacies/' . $pharmacy->id) }}" class="fg-btn-sm outline"><i class="bi bi-eye"></i> Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="fg-empty"><div class="ico"><i class="bi bi-shop"></i></div><h3>Belum ada pendaftaran</h3><p>Pendaftaran apotek baru akan tampil di sini.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $pharmacies->links('pagination::bootstrap-5') }}
@endsection
