<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Menampilkan formulir tambah pengguna
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pharmacy,customer',
            'is_active' => 'required|boolean',
        ]);

        User::create($validated);

        return redirect('/admin/users')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan formulir edit pengguna
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,pharmacy,customer',
            'is_active' => 'required|boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect('/admin/users')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // Menghapus pengguna
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect('/admin/users')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}