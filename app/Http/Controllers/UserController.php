<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(User::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$users = User::with(['permissions', 'roles'])->get();

		return UserResource::collection($users);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(User $user)
	{
		return new UserResource($user);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UserRequest $request, User $user)
	{
		try {
			DB::beginTransaction();

			$user->update([
				'name' => $request->name,
				'email' => $request->email
			]);

			$user->syncRoles($request->roles);

			$user->syncPermissions($request->permissions);

			DB::commit();

			return $this->okResponse(__('messages.data_saved'));
		} catch (\Exception $e) {
			DB::rollBack();

			return $this->serverErrorResponse();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		try {
			$user->delete();

			return $this->okResponse(__('messages.data_deleted'));
		} catch (\Exception $e) {
			return $this->serverErrorResponse(__('messages.data_cannot_be_deleted'));
		}
	}
}
