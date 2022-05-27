<?php

namespace Tests\Feature\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function can_login()
	{
		$user = User::factory()->create([
			'email' => 'user@example.test',
			'password' => bcrypt('password')
		]);

		$response = $this->postJson('/api/login', [
			'email' => $user->email,
			'password' => 'password'
		]);

		$response
			->assertStatus(200)
			->assertSee('accessToken');
	}

	/** @test */
	public function user_only_has_one_auth_token()
	{
		$user = User::factory()->create([
			'email' => 'user@example.test',
			'password' => bcrypt('password')
		]);

		$this->assertDatabaseCount('personal_access_tokens', 0);

		$this->postJson('/api/login', [
			'email' => $user->email,
			'password' => 'password'
		]);

		$this->postJson('/api/login', [
			'email' => $user->email,
			'password' => 'password'
		]);

		$this->assertDatabaseCount('personal_access_tokens', 1);
	}

	/** @test */
	public function can_give_permissions_to_auth_token()
	{
		$permissions = Permission::factory(3)->create();

		$user = User::factory()->create([
			'email' => 'user@example.test',
			'password' => bcrypt('password')
		]);

		$user->givePermissionTo($permissions);

		$this->postJson('/api/login', [
			'email' => $user->email,
			'password' => 'password'
		]);

		/** @var array */
		$tokenAbilities = $user->tokens->last()->abilities;

		$this->assertTrue(in_array($permissions[0]->name, $tokenAbilities));
		$this->assertTrue(in_array($permissions[1]->name, $tokenAbilities));
		$this->assertTrue(in_array($permissions[2]->name, $tokenAbilities));
	}
}
