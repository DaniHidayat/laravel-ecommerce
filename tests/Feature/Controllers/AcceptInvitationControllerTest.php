<?php

namespace Tests\Feature\Controllers;

use App\Models\Invitation;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AcceptInvitationControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function can_check_if_invitation_exists()
	{
		$unencryptedToken = Str::random(150);

		$invitation = Invitation::factory()->create([
			'token' => bcrypt($unencryptedToken)
		]);

		$response = $this->get("/api/invitations/$invitation->email/$unencryptedToken");

		$response
			->assertOk()
			->assertJson(['message' => 'OK']);
	}

	/** @test */
	public function can_check_if_invitation_doesnt_exist()
	{
		$emailDoesntExist = "email@random.test";

		$response = $this->get("/api/invitations/$emailDoesntExist/token");

		$response
			->assertNotFound()
			->assertJson(['message' => 'No invitation for you']);
	}

	/** @test */
	public function can_check_if_invitation_token_wrong()
	{
		$invitation = Invitation::factory()->create();

		$invalidToken = Str::random(150);

		$response = $this->get("/api/invitations/$invitation->email/$invalidToken");

		$response
			->assertNotFound()
			->assertJson(['message' => 'No invitation for you']);
	}

	/** @test */
	public function can_check_if_invitation_expired()
	{
		$unencryptedToken = Str::random(150);

		$invitation = Invitation::factory()->create([
			'token' => bcrypt($unencryptedToken),
			'expired_at' => Carbon::parse('yesterday')
		]);

		$response = $this->get("/api/invitations/$invitation->email/$unencryptedToken");

		$response
			->assertForbidden()
			->assertJson(['message' => 'Invitation has expired']);
	}

	/** @test */
	public function can_accept_invitation()
	{
		$unencryptedToken = Str::random(150);

		$invitation = Invitation::factory()->create([
			'token' => bcrypt($unencryptedToken)
		]);

		$roles = Role::factory(2)->create();

		$permissions = Permission::factory(2)->create();

		$invitation->assignRole($roles);
		$invitation->givePermissionTo($permissions);

		$data = [
			'name' => 'Shora',
			'password' => 'password',
			'password_confirmation' => 'password',
		];

		$response = $this->post("/api/invitations/$invitation->email/$unencryptedToken", $data);

		$response
			->assertOk()
			->assertJson(['message' => 'OK']);

		$this->assertDatabaseHas(User::class, [
			'name' => $data['name'],
			'email' => $invitation->email,
		]);

		$user = User::query()
			->where('name', $data['name'])
			->where('email', $invitation->email)
			->first();

		$this->assertNotNull($user);

		$this->assertTrue($user->hasAllRoles($roles));
		$this->assertTrue($user->hasAllPermissions($permissions));

		$this->assertDatabaseCount(Invitation::class, 0);
	}

	/** @test */
	public function cannot_accept_invitation_if_email_doesnt_exist()
	{
		$emailDoesntExist = "email@random.test";

		$response = $this->post("/api/invitations/$emailDoesntExist/token");

		$response
			->assertForbidden()
			->assertJson(['message' => 'Forbidden']);
	}

	/** @test */
	public function cannot_accept_invitation_if_token_wrong()
	{
		$invitation = Invitation::factory()->create();

		$response = $this->post("/api/invitations/$invitation->email/token");

		$response
			->assertForbidden()
			->assertJson(['message' => 'Forbidden']);
	}

	/** @test */
	public function cannot_accept_if_invitation_expired()
	{
		$unencryptedToken = Str::random(150);

		$invitation = Invitation::factory()->create([
			'token' => bcrypt($unencryptedToken),
			'expired_at' => Carbon::parse('yesterday')
		]);

		$response = $this->post("/api/invitations/$invitation->email/$unencryptedToken");

		$response
			->assertForbidden()
			->assertJson(['message' => 'Invitation has expired']);
	}
}
