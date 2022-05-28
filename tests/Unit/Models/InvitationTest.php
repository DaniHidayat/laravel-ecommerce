<?php

namespace Tests\Unit\Models;

use App\Models\Invitation;
use Tests\TestCase;

class InvitationTest extends TestCase
{
	/** @test */
	public function can_convert_expired_at_to_date_object()
	{
		$invitation = new Invitation(['expired_at' => '2020-12-12']);

		$this->assertIsObject($invitation->expired_at);
	}
}
