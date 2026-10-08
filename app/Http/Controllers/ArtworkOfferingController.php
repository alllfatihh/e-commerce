<?php

namespace App\Http\Controllers;

use App\Models\ArtworkOffering;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtworkOfferingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
            'offering_price' => 'required|numeric|min:0'
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan penawaran.'
            ], 401);
        }

        $artwork = Artwork::with('artist')->find($request->artwork_id);

        $offering = ArtworkOffering::create([
            'user_id' => auth()->id(),
            'artwork_id' => $request->artwork_id,
            'offering_price' => $request->offering_price,
            'status' => 'Dalam Tinjauan Kurasi',
            'reference_number' => 'NTS-ART-' . strtoupper(Str::random(6))
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'reference_number' => $offering->reference_number,
                'buyer_name' => auth()->user()->name,
                'buyer_email' => auth()->user()->email,
                'artwork_title' => $artwork->title,
                'artist_name' => $artwork->artist->name ?? 'Unknown',
                'offering_price' => $offering->offering_price,
                'status' => $offering->status
            ]
        ]);
    }
}
