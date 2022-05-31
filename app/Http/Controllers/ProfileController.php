<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function __invoke(Request $request)
	{
		return new UserResource($request->user());
	}
}
