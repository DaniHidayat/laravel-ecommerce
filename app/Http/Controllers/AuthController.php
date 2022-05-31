<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
	/**
	 * Handle an authentication attempt.
	 */
	public function login(Request $request)
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

	/**
	 * Register for customer.
	 */
	public function register(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'email' => 'required|max:255',
			'password' => 'required|confirmed'
		]);

		try {
			DB::transaction(function () use ($request) {

				$customer = User::create([
					'name' => $request->name,
					'email' => $request->email,
					'password' => $request->password,
				]);

				$customer->assignRole(RoleEnum::CUSTOMER);
			});

			return $this->okResponse();
		} catch (\Exception $e) {
			return $this->serverErrorResponse();
		}
	}
}
