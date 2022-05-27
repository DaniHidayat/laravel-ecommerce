<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = User::create([
			'name' => 'Shora',
			'email' => 'mail@shora.id',
			'password' => bcrypt('password'),
			'email_verified_at' => now()
		]);

		$permissions = Permission::all();

		$user->syncPermissions($permissions);
	}
}
