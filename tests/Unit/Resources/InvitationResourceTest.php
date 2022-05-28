<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class InvitationResourceTest extends TestCase
{
	/** @test */
	public function can_get_invitation_resource_in_correct_format()
	{
		$invitation = Invitation::factory()->make([
			'id' => 1,
			'created_at' => now()
		]);

		$roles = collect([
			Role::factory()->make()
		]);

		$permissions = collect([
			Permission::factory()->make()
		]);

		$invitation->setRelations([
			'roles' =>	$roles,
			'permissions' => $permissions
		]);

		$resource = (new InvitationResource($invitation))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $invitation->id,
				'name' => $invitation->name,
				'email' => $invitation->email,
				'expired_at' => $invitation->expired_at->format('d-m-Y'),
				'invited_at' => $invitation->created_at->format('d-m-Y'),
				'roles' => [
					$roles[0]->name
				],
				'permissions' => [
					$permissions[0]->name
				]
			]
		], $resource);
	}

	/** @test */
	public function can_get_invitation_resource_when_some_fields_are_null()
	{
		$invitation = Invitation::factory()->make([
			'id' => 1,
			'created_at' => now()
		]);

		$resource = (new InvitationResource($invitation))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $invitation->id,
				'name' => $invitation->name,
				'email' => $invitation->email,
				'expired_at' => $invitation->expired_at->format('d-m-Y'),
				'invited_at' => $invitation->created_at->format('d-m-Y'),
				'permissions' => [],
				'roles' => []
			]
		], $resource);
	}
}
