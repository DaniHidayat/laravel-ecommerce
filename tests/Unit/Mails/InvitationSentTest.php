<?php

namespace Tests\Unit\Mails;

use App\Mail\InvitationSent;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvitationSentTest extends TestCase
{
	/** @test */
	public function can_see_invitation_mail_content()
	{
		$invitation = Invitation::factory()->make();
		$unencryptedToken = Str::random(150);

		$mailable = new InvitationSent($invitation, $unencryptedToken);

		$mailable->assertSeeInHtml($invitation->name);
		$mailable->assertSeeInHtml($unencryptedToken);

		$mailable->assertSeeInText($invitation->name);
		$mailable->assertSeeInText($unencryptedToken);

		$appUrl = config('app.url');

		$mailable->assertSeeInText(
			"$appUrl/invitations/accept/$invitation->email/$unencryptedToken"
		);
	}
}
