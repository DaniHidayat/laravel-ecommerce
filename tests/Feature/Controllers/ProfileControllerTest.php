<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Base;

class ProfileControllerTest extends Base
{
	use RefreshDatabase;

	/** @test */
	public function authenticated_user_can_get_profile()
	{
		$this->setAuthToken();

		$response = $this->get('/api/me');

		$response
			->assertOk()
			->assertJsonStructure([
				'data' => [
					'id',
					'name',
					'email',
					'permissions' => [],
					'roles' => [],
				]
			]);
	}
}
