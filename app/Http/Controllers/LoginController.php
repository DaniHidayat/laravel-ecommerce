<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	/**
	 * Handle an authentication attempt.
	 */
	public function __invoke(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials)) {
			// Revoke auth token before create the new token
			$request->user()->tokens()->where('name', 'auth_token')->delete();

			// Get all user permissions
			$permissions = $request->user()->getPermissionNames()->toArray();

			// Give permissions to token
			$token = $request->user()->createToken('auth_token', $permissions);

			return response()->json([
				'accessToken' => $token->plainTextToken
			]);
		}

		return $this->unauthorizedResponse(__('messages.login_failed'));
	}
}
