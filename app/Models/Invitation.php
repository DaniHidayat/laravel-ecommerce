<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Invitation extends Model
{
	use HasFactory;
	use HasRoles, HasPermissions;

	protected $guard_name = 'web';

	protected $fillable = [
		'name',
		'email',
		'token',
		'expired_at'
	];

	protected $dates = [
		'expired_at'
	];
}
