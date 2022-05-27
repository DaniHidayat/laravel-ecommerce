<?php

namespace Tests\Feature\Controllers;

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
}
