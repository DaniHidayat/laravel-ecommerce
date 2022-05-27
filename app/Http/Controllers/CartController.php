<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CartRequest $request)
	{
		$cart = auth()->user()->cart;

		$product = $cart->products()->where('product_id', $request->product_id)->first();

		if ($product) {
			$oldQuantity = $product->pivot->quantity;
			$newQuantity = $oldQuantity + $request->quantity;

			$cart->products()->updateExistingPivot($request->product_id, ['quantity' => $newQuantity]);
		} else {
			$cart->products()->attach($request->product_id, ['quantity' => $request->quantity]);
		}

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show()
	{
		$cart = auth()->user()->cart;

		return new CartResource($cart);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(CartRequest $request)
	{
		$cart = auth()->user()->cart;

		$cart->products()->updateExistingPivot($request->product_id, ['quantity' => $request->quantity]);

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(CartRequest $request)
	{
		$cart = auth()->user()->cart;

		$cart->products()->detach($request->product_id);

		return $this->okResponse(__('messages.data_deleted'));
	}
}
