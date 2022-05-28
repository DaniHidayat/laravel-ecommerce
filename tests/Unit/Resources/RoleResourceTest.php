<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class RoleResourceTest extends TestCase
{
	/** @test */
	public function can_get_role_resource_in_correct_format()
	{
		$role = Role::factory()->make([
			'id' => 1
		]);

		$permissions = collect([
			Permission::factory()->make(['id' => 1])
		]);

		$role->setRelation('permissions', $permissions);

		$resource = (new RoleResource($role))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $role->id,
				'name' => $role->name,
				'permissions' => [
					$permissions[0]->name
				]
			]
		], $resource);
	}

	/** @test */
	public function can_get_role_resource_when_some_fields_are_null()
	{
		$role = Role::factory()->make([
			'id' => 1
		]);

		$resource = (new RoleResource($role))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $role->id,
				'name' => $role->name,
				'permissions' => []
			]
		], $resource);
	}
}
