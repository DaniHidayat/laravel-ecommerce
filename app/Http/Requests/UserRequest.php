<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name' => 'required|max:255',
			'email' => 'required|email|unique:users,id',
			'permissions' => 'present|array',
			'permissions.*' => 'exists:permissions,name',
			'roles' => 'present|array',
			'roles.*' => 'exists:roles,name',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'name' => __('app.users.name'),
			'email' => __('app.users.email'),
			'roles' => __('app.users.roles'),
			'roles.*' => __('app.users.roles'),
			'permissions' => __('app.users.permissions'),
			'permissions.*' => __('app.users.permissions'),
		];
	}
}
