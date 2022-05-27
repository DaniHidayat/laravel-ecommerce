<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		$totalPrice = 0;

		return [
			'products' => $this->products->map(function ($product) use (&$totalPrice) {
				$totalPrice += +$product->price * $product->pivot->quantity;

				return [
					'id' => $product->id,
					'name' => $product->name,
					'price' => $product->price,
					'quantity' => $product->pivot->quantity
				];
			}),
			'total_price' => $totalPrice
		];
	}
}
