<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->insert(
			[
				[
					'name' => 'Shora',
					'email' => 'shora@duck.com',
					'password' => bcrypt('password'),
					'email_verified_at' => now()
				],
				[
					'name' => 'Tester',
					'email' => 'rushcov@duck.com',
					'password' => bcrypt('password'),
					'email_verified_at' => now()
				],
			]
		);
	}
}
