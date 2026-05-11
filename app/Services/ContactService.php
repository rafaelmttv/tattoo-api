<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Studio;
use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactService
{
    /**
     * Map of allowed contactable types to their model classes.
     */
    private const CONTACTABLE_TYPES = [
        'App\Models\Customer' => Customer::class,
        'App\Models\TattooArtist' => TattooArtist::class,
        'App\Models\Studio' => Studio::class,
    ];

    /**
     * List contacts for a given contactable entity, verifying ownership.
     */
    public function listForOwner(string $contactableType, int $contactableId, User $user): Collection
    {
        $this->resolveAndAuthorizeOwner($contactableType, $contactableId, $user);

        return Contact::where('contactable_type', $contactableType)
            ->where('contactable_id', $contactableId)
            ->get();
    }

    /**
     * Find a contact by ID, verifying ownership.
     */
    public function find(int $id, User $user): Contact
    {
        $contact = Contact::findOrFail($id);

        $this->resolveAndAuthorizeOwner(
            $contact->contactable_type,
            $contact->contactable_id,
            $user
        );

        return $contact;
    }

    /**
     * Create a new contact, verifying ownership of the contactable entity.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data, User $user): Contact
    {
        $this->resolveAndAuthorizeOwner(
            $data['contactable_type'],
            $data['contactable_id'],
            $user
        );

        return Contact::create([
            'contactable_type' => $data['contactable_type'],
            'contactable_id' => $data['contactable_id'],
            'type' => $data['type'],
            'value' => $data['value'],
        ]);
    }

    /**
     * Update an existing contact, verifying ownership.
     *
     * @param array<string, mixed> $data
     */
    public function update(Contact $contact, array $data, User $user): Contact
    {
        $this->resolveAndAuthorizeOwner(
            $contact->contactable_type,
            $contact->contactable_id,
            $user
        );

        $contact->update($data);

        return $contact->fresh();
    }

    /**
     * Delete a contact, verifying ownership.
     */
    public function delete(Contact $contact, User $user): void
    {
        $this->resolveAndAuthorizeOwner(
            $contact->contactable_type,
            $contact->contactable_id,
            $user
        );

        $contact->delete();
    }

    /**
     * Resolve the contactable owner and verify that the authenticated user
     * is the owner. This eliminates the duplicated if/elseif/else chains.
     *
     * @throws AccessDeniedHttpException
     * @throws NotFoundHttpException
     */
    private function resolveAndAuthorizeOwner(string $type, int $id, User $user): Model
    {
        if (!array_key_exists($type, self::CONTACTABLE_TYPES)) {
            throw new AccessDeniedHttpException('Invalid contactable type.');
        }

        $modelClass = self::CONTACTABLE_TYPES[$type];

        $owner = $modelClass::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$owner) {
            throw new AccessDeniedHttpException(
                'You are not authorized to manage contacts for this entity.'
            );
        }

        return $owner;
    }
}
