@extends('admin.layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="fg-page-head d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div><h1>Kelola Pengguna</h1><p>Daftar akun dan akses pengguna Farmago.</p></div>
        <a href="{{ url('/admin/users/create') }}" class="fg-btn"><i class="bi bi-person-plus"></i> Tambah pengguna</a>
    </div>

    <div class="fg-card" style="overflow: hidden;">
        <div class="table-responsive">
            <table class="fg-table">
                <thead><tr><th>No.</th><th>Pengguna</th><th>Email</th><th>Role</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fg-muted">{{ $users->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td class="fg-muted">{{ $user->email }}</td>
                            <td><span class="fg-tag {{ $user->role === 'admin' ? 'info' : ($user->role === 'pharmacy' ? 'success' : 'neutral') }}">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="fg-pill {{ $user->is_active ? 'ok' : 'out' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="fg-btn-sm outline"><i class="bi bi-pencil"></i><span class="d-none d-md-inline">Edit</span></a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fg-btn-sm danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="bi bi-trash"></i><span class="d-none d-md-inline">Hapus</span></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="fg-empty"><div class="ico"><i class="bi bi-people"></i></div><h3>Belum ada pengguna</h3><p>Pengguna yang terdaftar akan tampil di sini.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $users->links('pagination::bootstrap-5') }}
@endsection
