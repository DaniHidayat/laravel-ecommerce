<?php

namespace Tests\Setup\Permissions;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvitationPermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			PermissionEnum::GET_ALL_INVITATIONS,
			PermissionEnum::GET_SELECTED_INVITATION,
			PermissionEnum::ADD_INVITATION,
			PermissionEnum::UPDATE_INVITATION,
			PermissionEnum::DELETE_INVITATION,
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
