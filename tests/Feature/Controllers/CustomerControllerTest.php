<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Customer;
use App\Models\User;
use Tests\Base;
use Tests\Setup\Permissions\CustomerPermissionSeeder;

class CustomerControllerTest extends Base
{
	private array $data;

	private Customer $customer;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(CustomerPermissionSeeder::class);

		$this->data = Customer::factory()->raw();

		$this->customer = Customer::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_customers()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_CUSTOMERS);

		$response = $this->get('/api/customers');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					0 => [
						'id',
						'name',
						'email',
						'joined_at',
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_get_selected_customer()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_CUSTOMER);

		$response = $this->get("/api/customers/{$this->customer->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
					'email',
					'joined_at',
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_customer()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_CUSTOMER);

		$response = $this->patch("/api/customers/{$this->customer->id}", $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(User::class, [
			'name' => $this->data['name'],
		]);
	}

	/** @test */
	public function authorized_user_can_delete_customer()
	{
		$this->setAuthToken(PermissionEnum::DELETE_CUSTOMER);

		$response = $this->delete("/api/customers/{$this->customer->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing(User::class, ['id' => $this->customer->id]);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_customers()
	{
		$this->setAuthToken();

		$response = $this->get('/api/customers');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_customer()
	{
		$this->setAuthToken();

		$response = $this->get("/api/customers/{$this->customer->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_customer()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/customers/{$this->customer->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_customer()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/customers/{$this->customer->id}");

		$response->assertForbidden();
	}
}
