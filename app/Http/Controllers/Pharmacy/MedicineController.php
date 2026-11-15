<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return redirect()->route('pharmacy.dashboard')
                ->with('warning', 'Fitur pengelolaan obat hanya dapat diakses setelah apotek diverifikasi.');
        }

        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));

        $query = $pharmacy->medicines();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $medicines = $query->latest()->paginate(10)->withQueryString();
        $categories = $pharmacy->medicines()->whereNotNull('category')->distinct()->pluck('category');

        return view('pharmacy.medicines.index', compact('pharmacy', 'medicines', 'categories', 'q', 'category'));
    }

    public function create()
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return redirect()->route('pharmacy.dashboard')
                ->with('warning', 'Fitur pengelolaan obat hanya dapat diakses setelah apotek diverifikasi.');
        }

        return view('pharmacy.medicines.create', compact('pharmacy'));
    }

    public function store(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return redirect()->route('pharmacy.dashboard')
                ->with('warning', 'Fitur pengelolaan obat hanya dapat diakses setelah apotek diverifikasi.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'is_available' => 'nullable|boolean',
        ]);

        $pharmacy->medicines()->create([
            ...$validated,
            'is_available' => $request->boolean('is_available', $validated['stock'] > 0),
        ]);

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function show(Medicine $medicine)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $medicine->loadCount('confirmationRequests');
        $recentRequests = $medicine->confirmationRequests()->with('customer')->latest()->take(5)->get();

        return view('pharmacy.medicines.show', compact('pharmacy', 'medicine', 'recentRequests'));
    }

    public function edit(Medicine $medicine)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        return view('pharmacy.medicines.edit', compact('pharmacy', 'medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'is_available' => 'nullable|boolean',
        ]);

        $medicine->update([
            ...$validated,
            'is_available' => $request->boolean('is_available', $validated['stock'] > 0),
        ]);

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $name = $medicine->name;
        $medicine->delete();

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', "Data obat '{$name}' berhasil dihapus.");
    }
}
