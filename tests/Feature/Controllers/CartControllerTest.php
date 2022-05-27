<?php

namespace Tests\Feature\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Tests\Base;

class CartControllerTest extends Base
{
	/** @test */
	public function customer_can_view_the_cart()
	{
		$this->setAuthToken();

		Cart::factory()
			->hasAttached(Product::factory(2), ['quantity' => 2])
			->create(['user_id' => $this->authenticatedUser->id]);

		$response = $this->get("/api/cart");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'products' => [
						0 => [
							'id',
							'name',
							'price',
							'quantity',
						],
					],
					'total_price'
				]
			]);
	}

	/** @test */
	public function customer_can_add_product_to_the_cart()
	{
		$this->setAuthToken();

		$cart = Cart::factory()
			->hasAttached(Product::factory(2), ['quantity' => 2])
			->create(['user_id' => $this->authenticatedUser->id]);

		$product = Product::factory()->create();

		$data = [
			'product_id' => $product->id,
			'quantity' => 5
		];

		$response = $this->post("/api/cart", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas('cart_has_products', [
			'cart_id' => $cart->id,
			'product_id' => $product->id,
			'quantity' => 5,
		]);
	}

	/** @test */
	public function add_the_same_product_in_the_cart_will_add_up_the_quantity()
	{
		$this->setAuthToken();

		$product = Product::factory()->create();

		$cart = Cart::factory()
			->hasAttached($product, ['quantity' => 3])
			->create(['user_id' => $this->authenticatedUser->id]);

		$data = [
			'product_id' => $product->id,
			'quantity' => 5
		];

		$response = $this->post("/api/cart", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseCount('cart_has_products', 1);

		$this->assertDatabaseHas('cart_has_products', [
			'cart_id' => $cart->id,
			'product_id' => $product->id,
			'quantity' => 8,
		]);
	}

	/** @test */
	public function customer_can_update_product_quantity_in_the_cart()
	{
		$this->setAuthToken();

		$product = Product::factory()->create();

		$cart = Cart::factory()
			->hasAttached($product, ['quantity' => 15])
			->create(['user_id' => $this->authenticatedUser->id]);

		$data = [
			'product_id' => $product->id,
			'quantity' => 10
		];

		$response = $this->patch("/api/cart", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseCount('cart_has_products', 1);

		$this->assertDatabaseHas('cart_has_products', [
			'cart_id' => $cart->id,
			'product_id' => $product->id,
			'quantity' => 10,
		]);
	}

	/** @test */
	public function customer_can_remove_product_from_the_cart()
	{
		$this->setAuthToken();

		$product = Product::factory()->create();

		Cart::factory()
			->hasAttached($product, ['quantity' => 15])
			->create(['user_id' => $this->authenticatedUser->id]);

		$data = [
			'product_id' => $product->id,
		];

		$response = $this->delete("/api/cart", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing('cart_has_products', [
			'product_id' => $product->id,
		]);
	}

	public function customer_must_login_can_view_the_cart()
	{
		$this->markTestSkipped();

		$response = $this->get("/api/cart");

		$response
			->assertUnauthorized()
			->assertJson(['message' => __('messages.must_login')]);
	}
}
