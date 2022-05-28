<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'expired_at' => $this->expired_at->format('d-m-Y'),
			'invited_at' => $this->created_at->format('d-m-Y'),
			'permissions' => $this->permissions->map(function ($permission) {
				return $permission->name;
			}),
			'roles' => $this->roles->map(function ($role) {
				return $role->name;
			}),
		];
	}
}
