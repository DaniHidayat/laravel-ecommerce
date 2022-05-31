<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call([
			RoleSeeder::class,
			PermissionSeeder::class
		]);

		$this->call(UserSeeder::class);

		Category::factory(3)->create();

		Product::factory(3)->create();

		User::factory(3)->create();
	}
}
