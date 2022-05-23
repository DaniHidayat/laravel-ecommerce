<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Product;
use Tests\Base;
use Tests\Setup\Permissions\ProductPermissionSeeder;

class ProductControllerTest extends Base
{
	private array $data;

	private Product $product;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(ProductPermissionSeeder::class);

		$this->data = Product::factory()->raw();
		$this->product = Product::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_products()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_PRODUCTS);

		$response = $this->get('/api/products');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					0 => [
						'id',
						'category_id',
						'description',
						'image',
						'name',
						'price',
						'category' => [
							'id',
							'name',
						]
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_add_product()
	{
		$this->setAuthToken(PermissionEnum::ADD_PRODUCT);

		$response = $this->post('/api/products', $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(Product::class, $this->data);
	}

	/** @test */
	public function authorized_user_can_get_selected_product()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_PRODUCT);

		$response = $this->get("/api/products/{$this->product->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'category_id',
					'description',
					'image',
					'name',
					'price',
					'category' => [
						'id',
						'name',
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_product()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_PRODUCT);

		$response = $this->patch("/api/products/{$this->product->id}", $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(Product::class, $this->data);
	}

	/** @test */
	public function authorized_user_can_delete_product()
	{
		$this->setAuthToken(PermissionEnum::DELETE_PRODUCT);

		$response = $this->delete("/api/products/{$this->product->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseCount(Product::class, 0);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_products()
	{
		$this->setAuthToken();

		$response = $this->get('/api/products');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_add_product()
	{
		$this->setAuthToken();

		$response = $this->post('/api/products', $this->data);

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_product()
	{
		$this->setAuthToken();

		$response = $this->get("/api/products/{$this->product->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_product()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/products/{$this->product->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_product()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/products/{$this->product->id}");

		$response->assertForbidden();
	}
}
