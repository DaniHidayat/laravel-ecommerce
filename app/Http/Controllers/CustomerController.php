<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(Customer::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$customers = User::with(['permissions', 'roles'])->paginate();

		return CustomerResource::collection($customers);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Customer $customer)
	{
		return new CustomerResource($customer);
	}

	/**
	 * TODO: add update email
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Customer $customer)
	{
		$customer->update([
			'name' => $request->name,
		]);

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Customer $customer)
	{
		try {
			$customer->delete();

			return $this->okResponse(__('messages.data_deleted'));
		} catch (\Throwable $th) {
			return $this->serverErrorResponse(__('messages.data_cannot_be_deleted'));
		}
	}
}
