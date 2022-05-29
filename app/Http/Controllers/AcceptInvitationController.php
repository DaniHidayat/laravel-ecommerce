<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcceptInvitationController extends Controller
{
	/**
	 * Check if invitation exists
	 */
	public function checkInvitation(string $email, string $unencryptedToken)
	{
		$invitation = Invitation::query()
			->where('email', $email)
			->first();

		if (!$invitation || !Hash::check($unencryptedToken, $invitation->token)) {
			return $this->notFoundResponse(__('messages.invitation_not_found'));
		}

		if ($invitation->expired_at->lessThan(now())) {
			return $this->forbiddenResponse(__('messages.invitation_expired'));
		}

		return $this->okResponse();
	}

	/**
	 * Create accounts for invited users
	 */
	public function acceptInvitation(Request $request, string $email, string $unencryptedToken)
	{
		$invitation = Invitation::query()
			->where('email', $email)
			->first();

		if (!$invitation || !Hash::check($unencryptedToken, $invitation->token)) {
			return $this->forbiddenResponse();
		}

		if ($invitation->expired_at->lessThan(now())) {
			return $this->forbiddenResponse(__('messages.invitation_expired'));
		}

		$request->validate(([
			'name' => 'required|max:255',
			'password' => 'required|max:50|confirmed'
		]));

		try {
			DB::transaction(function () use ($request, $invitation) {

				$user = User::create([
					'name' => $request->name,
					'email' => $invitation->email,
					'email_verified_at' => now(),
					'password' => bcrypt($request->password)
				]);

				$user->assignRole($invitation->roles);
				$user->givePermissionTo($invitation->permissions);

				$invitation->delete();
			});

			return $this->okResponse();
		} catch (\Exception $e) {
			return $this->serverErrorResponse();
		}
	}
}
