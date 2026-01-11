<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudioController extends Controller
{
    public function index()
    {
        return Studio::with('user')->get();
    }

    public function show($id)
    {
        return Studio::with('user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $studio = Studio::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return response()->json($studio, 201);
    }

    public function update(Request $request, $id)
    {
        $studio = Studio::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $studio->update($request->only(['name', 'address', 'description']));

        return response()->json($studio);
    }

    public function destroy($id)
    {
        $studio = Studio::where('user_id', Auth::id())->findOrFail($id);
        $studio->delete();

        return response()->json(['message' => 'Studio deleted']);
    }
}