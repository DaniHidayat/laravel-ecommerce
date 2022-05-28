<?php

namespace Database\Seeders;

use App\Models\Cart;
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
		// Superuser
		$user = User::create([
			'name' => 'Shora',
			'email' => 'mail@shora.id',
			'password' => bcrypt('password'),
			'email_verified_at' => now()
		]);

		$permissions = Permission::all();

		$user->syncPermissions($permissions);

		// Customer
		$customer = User::create([
			'name' => 'Customer',
			'email' => 'customer@shora.id',
			'password' => bcrypt('password'),
			'email_verified_at' => now()
		]);

		Cart::create([
			'user_id' => $customer->id,
		]);
	}
}
