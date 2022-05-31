<?php

namespace Tests\Feature\Controllers;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function guest_can_register()
	{
		Role::factory()->create(['name' => RoleEnum::CUSTOMER]);

		$data = [
			'name' => 'Kakak Dewa',
			'email' => 'guest@mail.test',
			'password' => 'password',
			'password_confirmation' => 'password',
		];

		$response = $this->post('/api/register', $data);

		$response
			->assertStatus(200)
			->assertJson(['message' => 'OK']);

		$savedCustomer = User::where('email', 'guest@mail.test')->first();

		$this->assertNotNull($savedCustomer);

		$this->assertTrue($savedCustomer->hasRole(RoleEnum::CUSTOMER));
	}
}
