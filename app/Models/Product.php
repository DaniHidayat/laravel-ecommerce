<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	use HasFactory;

	protected $fillable = [
		'category_id',
		'description',
		'image',
		'name',
		'price',
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
}
