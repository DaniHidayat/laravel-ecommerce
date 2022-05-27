<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartResourceTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function can_get_cart_resource_in_correct_format()
	{
		$products = Product::factory(2)->sequence(
			[
				'id' => 1,
				'name' => 'Laptop',
				'price' => 700,
			],
			[
				'id' => 2,
				'name' => 'Smartphone',
				'price' => 250,
			],
		)->create();

		$cart = Cart::factory()
			->hasAttached($products, ['quantity' => 2])
			->create();

		$resource = (new CartResource($cart))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'products' => [
					0 => [
						'id' => 1,
						'name' => 'Laptop',
						'price' => 700,
						'quantity' => 2,
					],
					1 => [
						'id' => 2,
						'name' => 'Smartphone',
						'price' => 250,
						'quantity' => 2,
					]
				],
				'total_price' => 1900
			]
		], $resource);
	}
}
