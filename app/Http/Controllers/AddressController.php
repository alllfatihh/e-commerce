<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Get all saved addresses for the authenticated user (max 3)
     */
    public function index()
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $addresses = Auth::user()->addresses;

        return response()->json([
            'success' => true,
            'count' => $addresses->count(),
            'max' => 3,
            'addresses' => $addresses,
        ]);
    }

    /**
     * Store a new address (strictly maximum 3 addresses)
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $currentCount = $user->addresses()->count();

        if ($currentCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 3 alamat yang dapat disimpan. Hapus alamat lama terlebih dahulu untuk menambahkan alamat baru.',
            ], 422);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:60',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'area_id' => 'required|string',
            'area_name' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $isPrimary = $request->boolean('is_primary') || ($currentCount === 0);

        if ($isPrimary) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address = $user->addresses()->create([
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'area_id' => $validated['area_id'],
            'area_name' => $validated['area_name'] ?? null,
            'is_primary' => $isPrimary,
        ]);

        if ($isPrimary) {
            $user->update([
                'address' => $validated['address'],
                'area_id' => $validated['area_id'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan.',
            'address' => $address,
            'addresses' => $user->fresh()->addresses,
        ]);
    }

    /**
     * Update an existing address
     */
    public function update(Request $request, $id)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $address = $user->addresses()->find($id);

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:60',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'area_id' => 'required|string',
            'area_name' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $isPrimary = $request->boolean('is_primary') || $address->is_primary;

        if ($request->has('is_primary') && $request->boolean('is_primary')) {
            $user->addresses()->where('id', '!=', $id)->update(['is_primary' => false]);
            $isPrimary = true;
        }

        $address->update([
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'area_id' => $validated['area_id'],
            'area_name' => $validated['area_name'] ?? $address->area_name,
            'is_primary' => $isPrimary,
        ]);

        if ($isPrimary) {
            $user->update([
                'address' => $validated['address'],
                'area_id' => $validated['area_id'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui.',
            'address' => $address,
            'addresses' => $user->fresh()->addresses,
        ]);
    }

    /**
     * Delete an address
     */
    public function destroy($id)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $address = $user->addresses()->find($id);

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan.'], 404);
        }

        $wasPrimary = $address->is_primary;
        $address->delete();

        // If the deleted address was primary, make the first remaining address primary
        $remaining = $user->fresh()->addresses;
        if ($wasPrimary && $remaining->isNotEmpty()) {
            $first = $remaining->first();
            $first->update(['is_primary' => true]);
            $user->update([
                'address' => $first->address,
                'area_id' => $first->area_id,
            ]);
        } elseif ($remaining->isEmpty()) {
            $user->update([
                'address' => null,
                'area_id' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus.',
            'addresses' => $user->fresh()->addresses,
        ]);
    }

    /**
     * Set an address as primary
     */
    public function setPrimary($id)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $address = $user->addresses()->find($id);

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan.'], 404);
        }

        $user->addresses()->where('id', '!=', $id)->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        $user->update([
            'address' => $address->address,
            'area_id' => $address->area_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat utama berhasil diubah.',
            'addresses' => $user->fresh()->addresses,
        ]);
    }
}
