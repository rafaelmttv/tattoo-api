<?php

namespace App\Http\Controllers;

use App\Models\TattooArtist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TattooArtistController extends Controller
{
    public function index()
    {
        return TattooArtist::with('user')->get();
    }

    public function show($id)
    {
        return TattooArtist::with('user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
        ]);

        $artist = TattooArtist::create([
            'user_id' => Auth::id(),
            'bio' => $request->bio,
            'experience_years' => $request->experience_years,
        ]);

        return response()->json($artist, 201);
    }

    public function update(Request $request, $id)
    {
        $artist = TattooArtist::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
        ]);

        $artist->update($request->only(['bio', 'experience_years']));

        return response()->json($artist);
    }

    public function destroy($id)
    {
        $artist = TattooArtist::where('user_id', Auth::id())->findOrFail($id);
        $artist->delete();

        return response()->json(['message' => 'TattooArtist deleted']);
    }
}