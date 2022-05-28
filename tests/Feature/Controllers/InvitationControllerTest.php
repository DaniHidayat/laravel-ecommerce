<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Mail\InvitationSent;
use App\Models\Invitation;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use Tests\Base;
use Tests\Setup\Permissions\InvitationPermissionSeeder;

class InvitationControllerTest extends Base
{
	private Invitation $invitation;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed(InvitationPermissionSeeder::class);

		$this->invitation = Invitation::factory()->create();
	}

	/** @test */
	public function authorized_user_can_get_all_invitations()
	{
		$this->setAuthToken(PermissionEnum::GET_ALL_INVITATIONS);

		$response = $this->get('/api/invitations');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					0 => [
						'id',
						'name',
						'email',
						'invited_at',
						'expired_at',
						'permissions' => [],
						'roles' => []
					]
				]
			]);
	}

	/** @test */
	public function authorized_user_can_create_invitation()
	{
		$this->setAuthToken(PermissionEnum::ADD_INVITATION);

		$data = Invitation::factory()->make([
			'roles' => [
				Role::factory()->create()->name
			],
			'permissions' => [
				PermissionEnum::GET_ALL_INVITATIONS
			],
		])->toArray();

		$response = $this->post('/api/invitations', $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$invitation = Invitation::where('name', $data['name'])->first();

		$this->assertNotNull($invitation);

		$this->assertTrue($invitation->hasAllPermissions($data['permissions']));

		$this->assertTrue($invitation->hasAllRoles($data['roles']));
	}

	/** @test */
	public function can_send_email_after_create_invitation()
	{
		Mail::fake();

		$this->setAuthToken(PermissionEnum::ADD_INVITATION);

		$this->post('/api/invitations', [
			'name' => 'Example name',
			'email' => 'example@numera.test',
			'roles' => [],
			'permissions' => [],
		]);

		Mail::assertSent(InvitationSent::class);
	}

	/** @test */
	public function authorized_user_can_get_selected_invitation()
	{
		$this->setAuthToken(PermissionEnum::GET_SELECTED_INVITATION);

		$response = $this->get("/api/invitations/{$this->invitation->id}");

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
					'email',
					'invited_at',
					'expired_at',
					'permissions' => [],
					'roles' => []
				]
			]);
	}

	/** @test */
	public function authorized_user_can_update_invitation()
	{
		$this->setAuthToken(PermissionEnum::UPDATE_INVITATION);

		$data = Invitation::factory()->make([
			'roles' => [
				Role::factory()->create()->name
			],
			'permissions' => [
				PermissionEnum::GET_ALL_INVITATIONS
			],
		])->toArray();

		$response = $this->patch("/api/invitations/{$this->invitation->id}", $data);

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_saved')]);

		$this->assertDatabaseHas(Invitation::class, [
			'name' => $data['name']
		]);

		$invitation = Invitation::where('name', $data['name'])->first();

		$this->assertTrue($invitation->hasAllPermissions($data['permissions']));

		$this->assertTrue($invitation->hasAllRoles($data['roles']));
	}

	/** @test */
	public function authorized_user_can_delete_invitation()
	{
		$this->setAuthToken(PermissionEnum::DELETE_INVITATION);

		$response = $this->delete("/api/invitations/{$this->invitation->id}");

		$response
			->assertOk()
			->assertJson(['message' => __('messages.data_deleted')]);

		$this->assertDatabaseMissing(Invitation::class, ['id' => $this->invitation->id]);
	}

	/** @test */
	public function unauthorized_user_cannot_get_all_invitations()
	{
		$this->setAuthToken();

		$response = $this->get('/api/invitations');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_create_invitation()
	{
		$this->setAuthToken();

		$response = $this->post('/api/invitations');

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_get_selected_invitation()
	{
		$this->setAuthToken();

		$response = $this->get("/api/invitations/{$this->invitation->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_update_invitation()
	{
		$this->setAuthToken();

		$response = $this->patch("/api/invitations/{$this->invitation->id}");

		$response->assertForbidden();
	}

	/** @test */
	public function unauthorized_user_cannot_delete_invitation()
	{
		$this->setAuthToken();

		$response = $this->delete("/api/invitations/{$this->invitation->id}");

		$response->assertForbidden();
	}
}
