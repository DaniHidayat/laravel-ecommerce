<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(Role::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$roles = Role::query()->with('permissions')->paginate();

		return RoleResource::collection($roles);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(RoleRequest $request)
	{
		DB::transaction(function () use ($request) {
			$role = Role::create([
				'name' => $request->name,
				'guard_name' => 'web'
			]);

			$role->givePermissionTo($request->permissions);
		});

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Role $role)
	{
		$role->load('permissions');

		return new RoleResource($role);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(RoleRequest $request, Role $role)
	{
		DB::transaction(function () use ($request, $role) {
			$role->update(['name' => $request->name]);

			$role->syncPermissions($request->permissions);
		});

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Role $role)
	{
		$role->delete();

		return $this->okResponse(__('messages.data_deleted'));
	}
}
