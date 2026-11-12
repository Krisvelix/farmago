<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    // Menampilkan formulir pendaftaran apotek
    public function create()
    {
        $pharmacy = auth()->user()->pharmacy;

        return view('pharmacy.register', compact('pharmacy'));
    }

    // Menyimpan pendaftaran apotek
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pharmacy_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Pharmacy::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                ...$validated,
                'verification_status' => 'pending',
                'rejection_reason' => null,
            ]
        );

        return redirect('/pharmacy/registration')
            ->with('success', 'Pendaftaran apotek berhasil dikirim. Silakan tunggu verifikasi Admin.');
    }
}