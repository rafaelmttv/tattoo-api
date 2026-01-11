<?php

namespace App\Http\Controllers;

use App\Models\ArtistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistServiceController extends Controller
{
    public function index()
    {
        return ArtistService::with('provider.user')->where('active', true)->get();
    }

    public function show($id)
    {
        return ArtistService::with('provider.user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $service = ArtistService::create([
            'provider_id' => Auth::user()->tattooArtist->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'active' => $request->active ?? true,
        ]);

        return response()->json($service, 201);
    }

    public function update(Request $request, $id)
    {
        $service = ArtistService::where('provider_id', Auth::user()->tattooArtist->id)->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $service->update($request->only(['name', 'description', 'price', 'duration', 'active']));

        return response()->json($service);
    }
}