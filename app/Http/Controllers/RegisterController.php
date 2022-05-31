<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
	public function __invoke(Request $request)
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
