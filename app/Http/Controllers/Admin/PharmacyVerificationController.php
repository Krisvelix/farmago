<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyVerificationController extends Controller
{
    // Menampilkan daftar pendaftaran apotek
    public function index()
    {
        $pharmacies = Pharmacy::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    // Menampilkan detail pendaftaran apotek
    public function show(Pharmacy $pharmacy)
    {
        $pharmacy->load('user');

        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    // Menyetujui pendaftaran apotek
    public function approve(Pharmacy $pharmacy)
    {
        $pharmacy->update([
            'verification_status' => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect('/admin/pharmacies')
            ->with('success', 'Pendaftaran apotek berhasil disetujui.');
    }

    // Menolak pendaftaran apotek
    public function reject(Request $request, Pharmacy $pharmacy)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $pharmacy->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect('/admin/pharmacies')
            ->with('success', 'Pendaftaran apotek berhasil ditolak.');
    }
}