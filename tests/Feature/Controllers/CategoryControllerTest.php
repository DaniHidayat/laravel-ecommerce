<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Category;
use Tests\Base;
use Tests\Setup\Permissions\CategoryPermissionSeeder;

class CategoryControllerTest extends Base
{
	private array $data;

	private Category $category;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(CategoryPermissionSeeder::class);

		$this->data = Category::factory()->raw();
		$this->category = Category::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_categories()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_CATEGORIES);

		$response = $this->get('/api/categories');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					0 => [
						'id',
						'name'
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_add_category()
	{
		$this->setAuthToken(PermissionEnum::ADD_CATEGORY);

		$response = $this->post('/api/categories', $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(Category::class, $this->data);
	}

	/** @test */
	public function authorized_user_can_get_selected_category()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_CATEGORY);

		$response = $this->get("/api/categories/{$this->category->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_category()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_CATEGORY);

		$response = $this->patch("/api/categories/{$this->category->id}", $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(Category::class, $this->data);
	}

	/** @test */
	public function authorized_user_can_delete_category()
	{
		$this->setAuthToken(PermissionEnum::DELETE_CATEGORY);

		$response = $this->delete("/api/categories/{$this->category->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing(Category::class, ['id' => $this->category->id]);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_categories()
	{
		$this->setAuthToken();

		$response = $this->get('/api/categories');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_add_category()
	{
		$this->setAuthToken();

		$response = $this->post('/api/categories', $this->data);

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_category()
	{
		$this->setAuthToken();

		$response = $this->get("/api/categories/{$this->category->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_category()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/categories/{$this->category->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_category()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/categories/{$this->category->id}");

		$response->assertForbidden();
	}
}
