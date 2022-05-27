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

			$token = $request->user()->createToken('auth_token', []);

			return response()->json([
				'accessToken' => $token->plainTextToken
			]);
		}

		return $this->unauthorizedResponse(__('messages.login_failed'));
	}
}
