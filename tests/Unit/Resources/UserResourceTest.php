<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
	private User $user;

	private Collection $roles;

	private Collection $permissions;

	public function setUp(): void
	{
		parent::setUp();

		$this->roles = collect([
			Role::factory()->make(['name' => 'Manager'])
		]);

		$this->permissions = collect([
			Permission::factory()->make(['name' => 'Create user'])
		]);

		$this->user = User::factory()->make([
			'id' => 1,
			'email' => 'john.doe@example.test',
			'name' => 'John Doe',
			'created_at' => Carbon::parse('25-05-2020')
		]);
	}

	/** @test */
	public function can_get_user_resource_in_correct_format()
	{
		// Set model relation
		$this->user->setRelations([
			'roles' => $this->roles,
			'permissions' => $this->permissions,
		]);

		$resource = (new UserResource($this->user))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => 1,
				'email' => 'john.doe@example.test',
				'joined_at' => '25-05-2020',
				'name' => 'John Doe',
				'permissions' => [
					'Create user'
				],
				'roles' => [
					'Manager'
				],
			]
		], $resource);
	}

	/** @test */
	public function can_get_user_resource_when_some_fields_are_null()
	{
		$resource = (new UserResource($this->user))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => 1,
				'email' => 'john.doe@example.test',
				'joined_at' => '25-05-2020',
				'name' => 'John Doe',
				'permissions' => [],
				'roles' => [],
			]
		], $resource);
	}
}
