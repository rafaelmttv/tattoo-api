<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    /**
     * List customers with pagination.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Customer::with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a customer by ID with relationships.
     */
    public function find(int $id): Customer
    {
        return Customer::with(['user', 'contacts'])->findOrFail($id);
    }

    /**
     * Create a new customer profile for the given user.
     *
     * @param array<string, mixed> $data
     */
    public function create(User $user, array $data): Customer
    {
        return Customer::create([
            'user_id' => $user->id,
            'birth_date' => $data['birth_date'] ?? null,
        ]);
    }

    /**
     * Update an existing customer profile.
     *
     * @param array<string, mixed> $data
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer->fresh(['user']);
    }

    /**
     * Delete a customer profile.
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }
}
