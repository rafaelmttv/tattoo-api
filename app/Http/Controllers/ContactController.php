<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ContactService $contactService
    ) {}

    /**
     * List contacts for a given contactable entity.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'contactable_type' => 'required|string',
            'contactable_id' => 'required|integer',
        ]);

        $contacts = $this->contactService->listForOwner(
            $request->contactable_type,
            $request->contactable_id,
            $request->user()
        );

        return ContactResource::collection($contacts);
    }

    /**
     * Show a single contact.
     */
    public function show(Request $request, int $id): ContactResource
    {
        $contact = $this->contactService->find($id, $request->user());

        return new ContactResource($contact);
    }

    /**
     * Create a new contact.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->create(
            $request->validated(),
            $request->user()
        );

        return $this->createdResponse(new ContactResource($contact));
    }

    /**
     * Update an existing contact.
     */
    public function update(UpdateContactRequest $request, int $id): ContactResource
    {
        $contact = Contact::findOrFail($id);

        $contact = $this->contactService->update(
            $contact,
            $request->validated(),
            $request->user()
        );

        return new ContactResource($contact);
    }

    /**
     * Delete a contact.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);

        $this->contactService->delete($contact, $request->user());

        return $this->noContentResponse();
    }
}