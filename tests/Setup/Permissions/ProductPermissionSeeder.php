<?php

namespace Tests\Setup\Permissions;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			PermissionEnum::GET_ALL_PRODUCTS,
			PermissionEnum::GET_SELECTED_PRODUCT,
			PermissionEnum::ADD_PRODUCT,
			PermissionEnum::UPDATE_PRODUCT,
			PermissionEnum::DELETE_PRODUCT,
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
