<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::with('user')->get();
    }

    public function show($id)
    {
        return Customer::with('user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'birth_date' => 'nullable|date',
        ]);

        $customer = Customer::create([
            'user_id' => Auth::id(),
            'birth_date' => $request->birth_date,
        ]);

        return response()->json($customer, 201);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'birth_date' => 'nullable|date',
        ]);

        $customer->update($request->only(['birth_date']));

        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = Customer::where('user_id', Auth::id())->findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted']);
    }
}