<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller Customer.
 *
 * CATATAN: tabel medicines & confirmation_requests belum dibuat,
 * jadi sementara memakai DATA CONTOH (dummy) di method data() / requestsData().
 * Nanti ganti dengan query Eloquent saat tabelnya sudah ada.
 */
class CustomerController extends Controller
{
    // Dashboard Customer
    public function dashboard()
    {
        $medicines = collect($this->data()['medicines'])->take(4);
        $requests = collect($this->requestsData())->take(3);

        return view('customer.index', [
            'popular' => $medicines,
            'pharmacyCount' => count($this->data()['pharmacies']),
            'medicineCount' => count($this->data()['medicines']),
            'recentRequests' => $requests,
        ]);
    }

    // Halaman pencarian obat
    public function medicines(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $medicines = collect($this->data()['medicines'])
            ->when($q !== '', fn ($c) => $c->filter(
                fn ($m) => stripos($m['name'], $q) !== false
            ))
            ->values();

        return view('customer.medicines.index', compact('medicines', 'q'));
    }

    // Detail obat + harga & stok per apotek
    public function showMedicine($id)
    {
        $medicine = collect($this->data()['medicines'])->firstWhere('id', (int) $id);
        abort_if(!$medicine, 404);

        return view('customer.medicines.show', compact('medicine'));
    }

    // Daftar apotek
    public function pharmacies()
    {
        $pharmacies = $this->data()['pharmacies'];

        return view('customer.pharmacies.index', compact('pharmacies'));
    }

    // Riwayat permintaan konfirmasi
    public function requests()
    {
        $requests = $this->requestsData();

        return view('customer.requests.index', compact('requests'));
    }

    // Kirim permintaan konfirmasi (sementara belum disimpan ke database)
    public function storeRequest(Request $request, $id)
    {
        $request->validate([
            'pharmacy_id' => 'required|integer',
        ]);

        return redirect()->route('customer.requests')
            ->with('success', 'Permintaan konfirmasi berhasil dikirim ke apotek.');
    }

    // ---------- DATA CONTOH ----------
    private function data(): array
    {
        $pharmacies = [
            ['id' => 1, 'name' => 'Apotek Sehat Sentosa', 'address' => 'Jl. Teuku Umar No. 12, Bandar Lampung', 'phone' => '0721-123456', 'medicine_count' => 24],
            ['id' => 2, 'name' => 'Apotek Kimia Farma Kedaton', 'address' => 'Jl. Pramuka No. 45, Kedaton', 'phone' => '0721-654321', 'medicine_count' => 58],
            ['id' => 3, 'name' => 'Apotek Medika Raya', 'address' => 'Jl. Z.A. Pagar Alam No. 8, Rajabasa', 'phone' => '0721-778899', 'medicine_count' => 31],
        ];

        $medicines = [
            ['id' => 1, 'name' => 'Paracetamol 500 mg', 'category' => 'Pereda demam & nyeri', 'description' => 'Obat untuk meredakan demam dan nyeri ringan hingga sedang, seperti sakit kepala dan sakit gigi.', 'offers' => [
                ['pharmacy_id' => 1, 'pharmacy' => 'Apotek Sehat Sentosa', 'address' => 'Jl. Teuku Umar No. 12', 'price' => 5000, 'stock' => 120],
                ['pharmacy_id' => 2, 'pharmacy' => 'Apotek Kimia Farma Kedaton', 'address' => 'Jl. Pramuka No. 45', 'price' => 6000, 'stock' => 8],
                ['pharmacy_id' => 3, 'pharmacy' => 'Apotek Medika Raya', 'address' => 'Jl. Z.A. Pagar Alam No. 8', 'price' => 5500, 'stock' => 0],
            ]],
            ['id' => 2, 'name' => 'Amoxicillin 500 mg', 'category' => 'Antibiotik (dengan resep)', 'description' => 'Antibiotik untuk infeksi bakteri. Penggunaan harus sesuai resep dokter.', 'offers' => [
                ['pharmacy_id' => 2, 'pharmacy' => 'Apotek Kimia Farma Kedaton', 'address' => 'Jl. Pramuka No. 45', 'price' => 15000, 'stock' => 40],
                ['pharmacy_id' => 3, 'pharmacy' => 'Apotek Medika Raya', 'address' => 'Jl. Z.A. Pagar Alam No. 8', 'price' => 14000, 'stock' => 5],
            ]],
            ['id' => 3, 'name' => 'Cetirizine 10 mg', 'category' => 'Antihistamin', 'description' => 'Obat untuk meredakan gejala alergi seperti bersin, gatal, dan hidung berair.', 'offers' => [
                ['pharmacy_id' => 1, 'pharmacy' => 'Apotek Sehat Sentosa', 'address' => 'Jl. Teuku Umar No. 12', 'price' => 8000, 'stock' => 30],
            ]],
            ['id' => 4, 'name' => 'Antasida Doen', 'category' => 'Obat maag', 'description' => 'Obat untuk meredakan gejala sakit maag dan kelebihan asam lambung.', 'offers' => [
                ['pharmacy_id' => 1, 'pharmacy' => 'Apotek Sehat Sentosa', 'address' => 'Jl. Teuku Umar No. 12', 'price' => 3000, 'stock' => 75],
                ['pharmacy_id' => 3, 'pharmacy' => 'Apotek Medika Raya', 'address' => 'Jl. Z.A. Pagar Alam No. 8', 'price' => 3500, 'stock' => 60],
            ]],
            ['id' => 5, 'name' => 'Vitamin C 500 mg', 'category' => 'Suplemen', 'description' => 'Suplemen untuk membantu menjaga daya tahan tubuh.', 'offers' => [
                ['pharmacy_id' => 2, 'pharmacy' => 'Apotek Kimia Farma Kedaton', 'address' => 'Jl. Pramuka No. 45', 'price' => 12000, 'stock' => 200],
            ]],
        ];

        foreach ($medicines as &$m) {
            $m['min_price'] = min(array_column($m['offers'], 'price'));
            $m['pharmacy_total'] = count($m['offers']);
        }
        unset($m);

        return compact('pharmacies', 'medicines');
    }

    private function requestsData(): array
    {
        return [
            ['date' => '14 Nov 2026 09:15', 'medicine' => 'Paracetamol 500 mg', 'pharmacy' => 'Apotek Sehat Sentosa', 'status' => 'available'],
            ['date' => '13 Nov 2026 16:40', 'medicine' => 'Amoxicillin 500 mg', 'pharmacy' => 'Apotek Medika Raya', 'status' => 'pending'],
            ['date' => '12 Nov 2026 11:02', 'medicine' => 'Cetirizine 10 mg', 'pharmacy' => 'Apotek Kimia Farma Kedaton', 'status' => 'unavailable'],
        ];
    }
}
