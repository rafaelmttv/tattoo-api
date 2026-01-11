<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'contactable_type' => 'required|string',
            'contactable_id' => 'required|integer',
        ]);

        // Check if the owner belongs to the authenticated user
        $type = $request->contactable_type;
        $id = $request->contactable_id;

        if ($type === 'App\Models\Customer') {
            $owner = \App\Models\Customer::where('id', $id)->where('user_id', Auth::id())->first();
        } elseif ($type === 'App\Models\TattooArtist') {
            $owner = \App\Models\TattooArtist::where('id', $id)->where('user_id', Auth::id())->first();
        } elseif ($type === 'App\Models\Studio') {
            $owner = \App\Models\Studio::where('id', $id)->where('user_id', Auth::id())->first();
        } else {
            abort(403);
        }

        if (!$owner) {
            abort(403);
        }

        return Contact::where('contactable_type', $type)->where('contactable_id', $id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'contactable_type' => 'required|string',
            'contactable_id' => 'required|integer',
            'type' => 'required|string',
            'value' => 'required|string',
        ]);

        $type = $request->contactable_type;
        $id = $request->contactable_id;

        // Check ownership
        if ($type === 'App\Models\Customer') {
            $owner = \App\Models\Customer::where('id', $id)->where('user_id', Auth::id())->first();
        } elseif ($type === 'App\Models\TattooArtist') {
            $owner = \App\Models\TattooArtist::where('id', $id)->where('user_id', Auth::id())->first();
        } elseif ($type === 'App\Models\Studio') {
            $owner = \App\Models\Studio::where('id', $id)->where('user_id', Auth::id())->first();
        } else {
            abort(403);
        }

        if (!$owner) {
            abort(403);
        }

        $contact = Contact::create($request->only(['contactable_type', 'contactable_id', 'type', 'value']));

        return response()->json($contact, 201);
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // Check ownership
        if ($contact->contactable_type === 'App\Models\Customer') {
            $owner = \App\Models\Customer::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\TattooArtist') {
            $owner = \App\Models\TattooArtist::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\Studio') {
            $owner = \App\Models\Studio::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } else {
            abort(403);
        }

        if (!$owner) {
            abort(403);
        }

        return $contact;
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        // Check ownership
        if ($contact->contactable_type === 'App\Models\Customer') {
            $owner = \App\Models\Customer::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\TattooArtist') {
            $owner = \App\Models\TattooArtist::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\Studio') {
            $owner = \App\Models\Studio::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } else {
            abort(403);
        }

        if (!$owner) {
            abort(403);
        }

        $request->validate([
            'type' => 'sometimes|required|string',
            'value' => 'sometimes|required|string',
        ]);

        $contact->update($request->only(['type', 'value']));

        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        // Check ownership
        if ($contact->contactable_type === 'App\Models\Customer') {
            $owner = \App\Models\Customer::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\TattooArtist') {
            $owner = \App\Models\TattooArtist::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } elseif ($contact->contactable_type === 'App\Models\Studio') {
            $owner = \App\Models\Studio::where('id', $contact->contactable_id)->where('user_id', Auth::id())->first();
        } else {
            abort(403);
        }

        if (!$owner) {
            abort(403);
        }

        $contact->delete();

        return response()->json(['message' => 'Contact deleted']);
    }
}