<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\Base;
use Tests\Setup\Permissions\UserPermissionSeeder;

class UserControllerTest extends Base
{
	private array $data;

	private User $user;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(UserPermissionSeeder::class);

		$this->data = User::factory()->raw([
			'roles' => [
				Role::factory()->create()->name
			],
			'permissions' => [
				Permission::factory()->create()->name
			]
		]);

		$this->user = User::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_users()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_USERS);

		$response = $this->get('/api/users');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					0 => [
						'id',
						'name',
						'email',
						'joined_at',
						'permissions',
						'roles',
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_get_selected_user()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_USER);

		$response = $this->get("/api/users/{$this->user->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
					'email',
					'joined_at',
					'permissions',
					'roles',
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_user()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_USER);

		$response = $this->patch("/api/users/{$this->user->id}", $this->data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(User::class, [
			'name' => $this->data['name'],
			'email' => $this->data['email']
		]);

		$updatedUser = User::where('email', $this->data['email'])->first();

		$this->assertTrue($updatedUser->hasAllRoles($this->data['roles']));

		$this->assertTrue($updatedUser->hasAllPermissions($this->data['permissions']));
	}

	/** @test */
	public function authorized_user_can_delete_user()
	{
		$this->setAuthToken(PermissionEnum::DELETE_USER);

		$response = $this->delete("/api/users/{$this->user->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing(User::class, ['id' => $this->user->id]);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_users()
	{
		$this->setAuthToken();

		$response = $this->get('/api/users');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_user()
	{
		$this->setAuthToken();

		$response = $this->get("/api/users/{$this->user->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_user()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/users/{$this->user->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_user()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/users/{$this->user->id}");

		$response->assertForbidden();
	}
}
