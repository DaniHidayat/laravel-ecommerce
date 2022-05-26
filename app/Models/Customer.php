<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends User
{
	// use HasFactory;

	protected $table = 'users';

	/**
	 * Create a new factory instance for the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
	 */
	protected static function newFactory()
	{
		return CustomerFactory::new();
	}
}
