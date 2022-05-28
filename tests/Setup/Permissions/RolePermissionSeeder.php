<?php

namespace Tests\Setup\Permissions;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			PermissionEnum::GET_ALL_ROLES,
			PermissionEnum::GET_SELECTED_ROLE,
			PermissionEnum::ADD_ROLE,
			PermissionEnum::UPDATE_ROLE,
			PermissionEnum::DELETE_ROLE,
		];

		$data = [];

		$count = count($permissions);

		for ($i = 0; $i < $count; $i++) {
			$data[] = [
				'name' => $permissions[$i],
				'guard_name' => 'web',
			];
		}

		DB::table('permissions')->insert($data);
	}
}
