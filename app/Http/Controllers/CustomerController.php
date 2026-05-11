<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CustomerService $customerService
    ) {}

    /**
     * List all customers with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $customers = $this->customerService->list(
            perPage: $request->integer('per_page', 15)
        );

        return CustomerResource::collection($customers);
    }

    /**
     * Show a single customer.
     */
    public function show(int $id): CustomerResource
    {
        $customer = $this->customerService->find($id);

        return new CustomerResource($customer);
    }

    /**
     * Create a new customer profile.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->create(
            $request->user(),
            $request->validated()
        );

        return $this->createdResponse(new CustomerResource($customer));
    }

    /**
     * Update an existing customer profile.
     */
    public function update(UpdateCustomerRequest $request, int $id): CustomerResource
    {
        $customer = $this->customerService->find($id);

        $this->authorize('update', $customer);

        $customer = $this->customerService->update($customer, $request->validated());

        return new CustomerResource($customer);
    }

    /**
     * Delete a customer profile.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $customer = $this->customerService->find($id);

        $this->authorize('delete', $customer);

        $this->customerService->delete($customer);

        return $this->noContentResponse();
    }
}