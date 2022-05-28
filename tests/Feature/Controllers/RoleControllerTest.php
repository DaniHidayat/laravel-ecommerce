<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Role;
use Tests\Base;
use Tests\Setup\Permissions\RolePermissionSeeder;

class RoleControllerTest extends Base
{
	private Role $role;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(RolePermissionSeeder::class);

		$this->role = Role::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_roles()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_ROLES);

		$response = $this->get('/api/roles');

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
	public function authorized_user_can_create_new_role()
	{
		$this->setAuthToken(PermissionEnum::ADD_ROLE);

		$data = [
			'name' => 'Administrator',
			'permissions' => [
				PermissionEnum::GET_ALL_ROLES
			]
		];

		$response = $this->post('/api/roles', $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$role = Role::where('name', 'Administrator')->first();

		$this->assertNotNull($role);

		$this->assertTrue($role->hasPermissionTo(PermissionEnum::GET_ALL_ROLES));
	}

	/** @test */
	public function authorized_user_can_get_selected_role()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_ROLE);

		$response = $this->get("/api/roles/{$this->role->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
					'permissions' => []
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_role()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_ROLE);

		$data = [
			'name' => 'Administrator',
			'permissions' => [
				PermissionEnum::GET_ALL_ROLES
			]
		];

		$response = $this->patch("/api/roles/{$this->role->id}", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertEquals('Administrator', $this->role->refresh()->name);

		$this->assertTrue($this->role->hasPermissionTo(PermissionEnum::GET_ALL_ROLES));
	}

	/** @test */
	public function authorized_user_can_delete_role()
	{
		$this->setAuthToken(PermissionEnum::DELETE_ROLE);

		$response = $this->delete("/api/roles/{$this->role->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing(Role::class, ['id' => $this->role->id]);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_roles()
	{
		$this->setAuthToken();

		$response = $this->get('/api/roles');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_create_new_role()
	{
		$this->setAuthToken();

		$response = $this->post('/api/roles');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_role()
	{
		$this->setAuthToken();

		$response = $this->get("/api/roles/{$this->role->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_role()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/roles/{$this->role->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_role()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/roles/{$this->role->id}");

		$response->assertForbidden();
	}
}
