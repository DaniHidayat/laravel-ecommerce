<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Mail\InvitationSent;
use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(Invitation::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$invitations = Invitation::all();

		return InvitationResource::collection($invitations);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(InvitationRequest $request)
	{
		DB::transaction(function () use ($request) {

			$unencryptedToken = Str::random(150);

			$invitation = Invitation::create([
				'name' => $request->name,
				'email' => $request->email,
				'token' => bcrypt($unencryptedToken),
				'expired_at' => Carbon::parse('+12 hours')
			]);

			$invitation->assignRole($request->roles);

			$invitation->givePermissionTo($request->permissions);

			Mail::to($invitation)->send(new InvitationSent($invitation, $unencryptedToken));
		});

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Invitation $invitation)
	{
		$invitation->load('roles', 'permissions');

		return new InvitationResource($invitation);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(InvitationRequest $request, Invitation $invitation)
	{
		$invitation->update([
			'name' => $request->name,
		]);

		$invitation->syncRoles($request->roles);

		$invitation->syncPermissions($request->permissions);

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Invitation $invitation)
	{
		$invitation->delete();

		return $this->okResponse(__('messages.data_deleted'));
	}
}
