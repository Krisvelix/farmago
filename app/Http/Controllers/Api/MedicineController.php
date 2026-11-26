<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $medicines = Medicine::query()
            ->with('pharmacy:id,pharmacy_name,address,phone')
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->whereHas('pharmacy', fn ($query) => $query->where('verification_status', 'approved'))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get()
            ->map(fn (Medicine $medicine) => $this->medicinePayload($medicine))
            ->values();

        return response()->json([
            'data' => $medicines,
            'meta' => ['count' => $medicines->count()],
        ]);
    }

    public function pharmacies(): JsonResponse
    {
        $pharmacies = Pharmacy::query()
            ->where('verification_status', 'approved')
            ->withCount(['medicines' => fn ($query) => $query->where('is_available', true)->where('stock', '>', 0)])
            ->latest()
            ->get(['id', 'pharmacy_name', 'address', 'phone'])
            ->map(fn (Pharmacy $pharmacy) => [
                'id' => $pharmacy->id,
                'name' => $pharmacy->pharmacy_name,
                'address' => $pharmacy->address,
                'phone' => $pharmacy->phone,
                'available_medicine_count' => $pharmacy->medicines_count,
            ])
            ->values();

        return response()->json([
            'data' => $pharmacies,
            'meta' => ['count' => $pharmacies->count()],
        ]);
    }

    private function medicinePayload(Medicine $medicine): array
    {
        return [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'category' => $medicine->category,
            'description' => $medicine->description,
            'price' => $medicine->price,
            'stock' => $medicine->stock,
            'unit' => $medicine->unit,
            'pharmacy' => [
                'id' => $medicine->pharmacy->id,
                'name' => $medicine->pharmacy->pharmacy_name,
                'address' => $medicine->pharmacy->address,
            ],
        ];
    }
}
