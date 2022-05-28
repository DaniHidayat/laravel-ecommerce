<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			// Categories
			PermissionEnum::GET_ALL_CATEGORIES,
			PermissionEnum::GET_SELECTED_CATEGORY,
			PermissionEnum::ADD_CATEGORY,
			PermissionEnum::UPDATE_CATEGORY,
			PermissionEnum::DELETE_CATEGORY,

			// Customers
			PermissionEnum::GET_ALL_CUSTOMERS,
			PermissionEnum::GET_SELECTED_CUSTOMER,
			PermissionEnum::UPDATE_CUSTOMER,
			PermissionEnum::DELETE_CUSTOMER,

			// Invitations
			PermissionEnum::GET_ALL_INVITATIONS,
			PermissionEnum::GET_SELECTED_INVITATION,
			PermissionEnum::ADD_INVITATION,
			PermissionEnum::UPDATE_INVITATION,
			PermissionEnum::DELETE_INVITATION,

			// Products
			PermissionEnum::GET_ALL_PRODUCTS,
			PermissionEnum::GET_SELECTED_PRODUCT,
			PermissionEnum::ADD_PRODUCT,
			PermissionEnum::UPDATE_PRODUCT,
			PermissionEnum::DELETE_PRODUCT,

			// Users
			PermissionEnum::GET_ALL_USERS,
			PermissionEnum::GET_SELECTED_USER,
			PermissionEnum::ADD_USER,
			PermissionEnum::UPDATE_USER,
			PermissionEnum::DELETE_USER,
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
