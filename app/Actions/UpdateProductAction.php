<?php

namespace App\Actions;

use App\Models\Product;

class UpdateProductAction
{
	public function execute(Product $product, array $data): Product
	{
		$product->update([
			'category_id' => $data['category_id'],
			'description' => $data['description'],
			'image' => $data['image'],
			'name' => $data['name'],
			'price' => $data['price'],
		]);

		$product->specifications()->delete();
		$product->specifications()->insert($data['specifications']);

		return $product;
	}
}
