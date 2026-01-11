<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\TattooArtist;
use App\Models\StudioTattooArtist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudioTattooArtistController extends Controller
{
    public function index($studioId)
    {
        $studio = Studio::findOrFail($studioId);
        return $studio->tattooArtists()->with('user')->get();
    }

    public function store(Request $request, $studioId)
    {
        $studio = Studio::where('user_id', Auth::id())->findOrFail($studioId);

        $request->validate([
            'tattoo_artist_id' => 'required|exists:tattoo_artists,id',
        ]);

        $tattooArtist = TattooArtist::findOrFail($request->tattoo_artist_id);

        if ($studio->tattooArtists()->where('tattoo_artist_id', $tattooArtist->id)->exists()) {
            return response()->json(['message' => 'Already associated'], 409);
        }

        $studio->tattooArtists()->attach($tattooArtist);

        return response()->json(['message' => 'Associated'], 201);
    }

    public function destroy($studioId, $tattooArtistId)
    {
        $studio = Studio::where('user_id', Auth::id())->findOrFail($studioId);
        $tattooArtist = TattooArtist::findOrFail($tattooArtistId);

        $studio->tattooArtists()->detach($tattooArtist);

        return response()->json(['message' => 'Disassociated']);
    }
}