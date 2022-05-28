<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class Base extends TestCase
{
	use RefreshDatabase;

	protected User $authenticatedUser;

	/**
	 * Set Sanctum auth token with abilities
	 *
	 * @param string[]|null $permissions
	 */
	protected function setAuthToken(...$permissions): void
	{
		$this->authenticatedUser = User::factory()->create();

		Sanctum::actingAs(
			$this->authenticatedUser,
			$permissions,
		);
	}
}
