<?php

namespace Tests\Setup\Permissions;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryPermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			PermissionEnum::GET_ALL_CATEGORIES,
			PermissionEnum::GET_SELECTED_CATEGORY,
			PermissionEnum::ADD_CATEGORY,
			PermissionEnum::UPDATE_CATEGORY,
			PermissionEnum::DELETE_CATEGORY,
		];

		$permissions = collect($permissions)->map(function ($permission) {
			return [
				'name' => $permission,
				'guard_name' => 'web',
			];
		})->toArray();

		DB::table('permissions')->insert($permissions);
	}
}
