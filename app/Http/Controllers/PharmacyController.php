<?php

namespace App\Http\Controllers;

use App\Models\ConfirmationRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;

        $stats = [
            'total_medicines' => 0,
            'low_stock_count' => 0,
            'out_of_stock_count' => 0,
            'pending_requests_count' => 0,
            'total_requests_count' => 0,
        ];

        $recentMedicines = collect();
        $recentRequests = collect();

        if ($pharmacy && $pharmacy->verification_status === 'approved') {
            $stats['total_medicines'] = $pharmacy->medicines()->count();
            $stats['low_stock_count'] = $pharmacy->medicines()->where('stock', '<=', 10)->where('stock', '>', 0)->count();
            $stats['out_of_stock_count'] = $pharmacy->medicines()->where('stock', '<=', 0)->count();
            $stats['pending_requests_count'] = $pharmacy->confirmationRequests()->where('status', 'pending')->count();
            $stats['total_requests_count'] = $pharmacy->confirmationRequests()->count();

            $recentMedicines = $pharmacy->medicines()->latest()->take(5)->get();
            $recentRequests = $pharmacy->confirmationRequests()->with(['customer', 'medicine'])->latest()->take(5)->get();
        }

        return view('pharmacy.index', compact('pharmacy', 'stats', 'recentMedicines', 'recentRequests'));
    }

    public function create()
    {
        $pharmacy = auth()->user()->pharmacy;

        return view('pharmacy.register', compact('pharmacy'));
    }

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

        return redirect()->route('pharmacy.registration')
            ->with('success', 'Pendaftaran apotek berhasil dikirim. Silakan tunggu verifikasi Admin.');
    }

    public function profile()
    {
        $pharmacy = auth()->user()->pharmacy;

        return view('pharmacy.profile', compact('pharmacy'));
    }

    public function updateProfile(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.registration')
                ->with('warning', 'Silakan lakukan pendaftaran apotek terlebih dahulu.');
        }

        $validated = $request->validate([
            'pharmacy_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $pharmacy->update($validated);

        return redirect()->route('pharmacy.profile')
            ->with('success', 'Profil apotek berhasil diperbarui.');
    }

    public function stock(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return redirect()->route('pharmacy.dashboard')
                ->with('warning', 'Fitur pengelolaan stok dan harga hanya dapat diakses setelah apotek diverifikasi.');
        }

        $q = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');

        $query = $pharmacy->medicines();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            });
        }

        if ($filter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($filter === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($filter === 'available') {
            $query->where('stock', '>', 0)->where('is_available', true);
        }

        $medicines = $query->latest()->paginate(10)->withQueryString();

        return view('pharmacy.stock', compact('pharmacy', 'medicines', 'q', 'filter'));
    }

    public function updateStock(Request $request, Medicine $medicine)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $medicine->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $validated = $request->validate([
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $medicine->update([
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'is_available' => $request->boolean('is_available', $validated['stock'] > 0),
        ]);

        return back()->with('success', "Stok dan harga obat '{$medicine->name}' berhasil diperbarui.");
    }

    public function requests(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $pharmacy->verification_status !== 'approved') {
            return redirect()->route('pharmacy.dashboard')
                ->with('warning', 'Fitur permintaan konfirmasi hanya dapat diakses setelah apotek diverifikasi.');
        }

        $status = $request->query('status', 'all');

        $query = $pharmacy->confirmationRequests()->with(['customer', 'medicine']);

        if (in_array($status, ['pending', 'available', 'unavailable'])) {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => $pharmacy->confirmationRequests()->count(),
            'pending' => $pharmacy->confirmationRequests()->where('status', 'pending')->count(),
            'available' => $pharmacy->confirmationRequests()->where('status', 'available')->count(),
            'unavailable' => $pharmacy->confirmationRequests()->where('status', 'unavailable')->count(),
        ];

        return view('pharmacy.requests.index', compact('pharmacy', 'requests', 'status', 'counts'));
    }

    public function respondRequest(Request $request, ConfirmationRequest $confirmationRequest)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || $confirmationRequest->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:available,unavailable',
            'response_note' => 'nullable|string|max:500',
        ]);

        $confirmationRequest->update([
            'status' => $validated['status'],
            'response_note' => $validated['response_note'],
            'responded_at' => now(),
        ]);

        $label = $validated['status'] === 'available' ? 'Tersedia' : 'Tidak Tersedia';

        return back()->with('success', "Permintaan konfirmasi berhasil direspons sebagai '{$label}'.");
    }
}
