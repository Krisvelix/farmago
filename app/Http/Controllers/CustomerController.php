<?php

namespace App\Http\Controllers;

use App\Models\ConfirmationRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
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

    public function showMedicine($id)
    {
        $medicine = collect($this->data()['medicines'])->firstWhere('id', (int) $id);
        abort_if(!$medicine, 404);

        return view('customer.medicines.show', compact('medicine'));
    }

    public function pharmacies()
    {
        $pharmacies = $this->data()['pharmacies'];

        return view('customer.pharmacies.index', compact('pharmacies'));
    }

    public function requests()
    {
        $requests = $this->requestsData();

        return view('customer.requests.index', compact('requests'));
    }

    public function storeRequest(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'pharmacy_id' => 'required|integer|exists:pharmacies,id',
                'quantity' => 'nullable|integer|min:1',
                'note' => 'nullable|string|max:255',
            ],
            [
                'pharmacy_id.required' => 'Apotek wajib dipilih.',
                'pharmacy_id.integer' => 'ID apotek tidak valid.',
                'pharmacy_id.exists' => 'Apotek tidak ditemukan.',
                'quantity.integer' => 'Jumlah obat harus berupa angka.',
                'quantity.min' => 'Jumlah obat minimal 1.',
                'note.string' => 'Catatan harus berupa teks.',
                'note.max' => 'Catatan tidak boleh lebih dari 255 karakter.',
            ]
        );

        $pharmacyId = (int) $validated['pharmacy_id'];
        $pharmacy = Pharmacy::find($pharmacyId);
        $medicineGroup = Medicine::find($id);
        $medicine = $medicineGroup
            ? Medicine::where('name', $medicineGroup->name)
                ->where('pharmacy_id', $pharmacyId)
                ->first()
            : null;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return back()->withInput()->with('error', 'Apotek belum disetujui atau tidak ditemukan.');
        }

        if (!$medicine) {
            return back()->withInput()->with('error', 'Obat tidak tersedia di apotek yang dipilih.');
        }

        if ((int) $medicine->pharmacy_id !== $pharmacy->id) {
            return back()->withInput()->with('error', 'Obat tidak tersedia di apotek yang dipilih.');
        }

        if (!$medicine->is_available) {
            return back()->withInput()->with('error', 'Obat sedang tidak tersedia.');
        }

        ConfirmationRequest::create([
            'customer_id' => auth()->id(),
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $medicine->id,
            'quantity' => $validated['quantity'] ?? 1,
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.requests')
            ->with('success', 'Permintaan konfirmasi berhasil dikirim ke apotek.');
    }

    private function data(): array
    {
        $dbMedicines = Medicine::with('pharmacy')
            ->where('is_available', true)
            ->whereHas('pharmacy', function ($q) {
                $q->where('verification_status', 'approved');
            })
            ->get();

        if ($dbMedicines->isNotEmpty()) {
            $pharmaciesMap = [];
            $medicinesGrouped = [];

            foreach ($dbMedicines as $med) {
                $pharmacy = $med->pharmacy;
                if ($pharmacy) {
                    if (!isset($pharmaciesMap[$pharmacy->id])) {
                        $pharmaciesMap[$pharmacy->id] = [
                            'id' => $pharmacy->id,
                            'name' => $pharmacy->pharmacy_name,
                            'address' => $pharmacy->address,
                            'phone' => $pharmacy->phone,
                            'medicine_count' => 0,
                        ];
                    }
                    $pharmaciesMap[$pharmacy->id]['medicine_count']++;
                }

                $key = strtolower(trim($med->name));
                if (!isset($medicinesGrouped[$key])) {
                    $medicinesGrouped[$key] = [
                        'id' => $med->id,
                        'name' => $med->name,
                        'category' => $med->category ?? 'Umum',
                        'description' => $med->description ?? '',
                        'offers' => [],
                    ];
                }

                if ($pharmacy) {
                    $medicinesGrouped[$key]['offers'][] = [
                        'pharmacy_id' => $pharmacy->id,
                        'pharmacy' => $pharmacy->pharmacy_name,
                        'address' => $pharmacy->address,
                        'price' => $med->price,
                        'stock' => $med->stock,
                    ];
                }
            }

            $medicines = array_values($medicinesGrouped);
            foreach ($medicines as &$m) {
                $m['min_price'] = count($m['offers']) > 0 ? min(array_column($m['offers'], 'price')) : 0;
                $m['pharmacy_total'] = count($m['offers']);
            }
            unset($m);

            $pharmacies = array_values($pharmaciesMap);

            return compact('pharmacies', 'medicines');
        }

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
        if (auth()->check()) {
            $userRequests = ConfirmationRequest::where('customer_id', auth()->id())
                ->with(['medicine', 'pharmacy'])
                ->latest()
                ->get();

            if ($userRequests->isNotEmpty()) {
                return $userRequests->map(function ($r) {
                    return [
                        'date' => $r->created_at->translatedFormat('d M Y H:i'),
                        'medicine' => $r->medicine->name ?? 'Obat',
                        'pharmacy' => $r->pharmacy->pharmacy_name ?? 'Apotek',
                        'quantity' => $r->quantity,
                        'note' => $r->note,
                        'status' => $r->status,
                        'response_note' => $r->response_note,
                        'responded_at' => $r->responded_at?->translatedFormat('d M Y H:i'),
                    ];
                })->toArray();
            }
        }

        return [
            ['date' => '14 Nov 2026 09:15', 'medicine' => 'Paracetamol 500 mg', 'pharmacy' => 'Apotek Sehat Sentosa', 'quantity' => 1, 'note' => null, 'status' => 'available', 'response_note' => null, 'responded_at' => null],
            ['date' => '13 Nov 2026 16:40', 'medicine' => 'Amoxicillin 500 mg', 'pharmacy' => 'Apotek Medika Raya', 'quantity' => 1, 'note' => null, 'status' => 'pending', 'response_note' => null, 'responded_at' => null],
            ['date' => '12 Nov 2026 11:02', 'medicine' => 'Cetirizine 10 mg', 'pharmacy' => 'Apotek Kimia Farma Kedaton', 'quantity' => 1, 'note' => null, 'status' => 'unavailable', 'response_note' => null, 'responded_at' => null],
        ];
    }
}
