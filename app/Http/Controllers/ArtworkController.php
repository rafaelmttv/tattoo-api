<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $query = Artwork::with('creator.user')->where('active', true);

        if ($request->has('body_location')) {
            $query->where('body_location', $request->body_location);
        }

        return $query->paginate(10);
    }

    public function show($id)
    {
        return Artwork::with('creator.user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'body_location' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'active' => 'boolean',
        ]);

        $artwork = Artwork::create([
            'creator_id' => Auth::user()->tattooArtist->id,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'body_location' => $request->body_location,
            'price' => $request->price,
            'active' => $request->active ?? true,
        ]);

        return response()->json($artwork, 201);
    }

    public function update(Request $request, $id)
    {
        $artwork = Artwork::where('creator_id', Auth::user()->tattooArtist->id)->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'body_location' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'active' => 'boolean',
        ]);

        $artwork->update($request->only(['name', 'description', 'image_url', 'body_location', 'price', 'active']));

        return response()->json($artwork);
    }
}