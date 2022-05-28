<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationSent extends Mailable
{
	use Queueable, SerializesModels;

	private Invitation $invitation;

	private string $unencryptedToken;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(Invitation $invitation, string $unencryptedToken)
	{
		$this->invitation = $invitation;

		$this->unencryptedToken = $unencryptedToken;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$appUrl = config('app.url');

		$url = "$appUrl/invitations/accept/{$this->invitation->email}/{$this->unencryptedToken}";

		return $this
			->from('mail@shora.id', 'Admin')
			->markdown('emails.invitation-sent', [
				'invitation' => $this->invitation,
				'unencryptedToken' => $this->unencryptedToken,
				'url' => $url
			]);
	}
}
