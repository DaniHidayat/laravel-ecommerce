<?php

namespace App\Actions;

use App\Models\Product;

class CreateProductAction
{
	public function execute(array $data): Product
	{
		$product = Product::query()->create([
			'category_id' => $data['category_id'],
			'description' => $data['description'],
			'image' => $data['image'],
			'name' => $data['name'],
			'price' => $data['price'],
		]);

		$product->specifications()->insert($data['specifications']);

		return $product;
	}
}
