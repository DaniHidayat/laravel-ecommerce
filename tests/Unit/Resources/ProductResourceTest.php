<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
	/** @test */
	public function can_get_product_resource_in_correct_format()
	{
		$category = Category::factory()->make(['id' => 1]);

		$product = Product::factory()->make(['id' => 1]);

		$product->setRelation('category', $category);

		$resource = (new ProductResource($product))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $product->id,
				'category_id' => $product->category_id,
				'description' => $product->description,
				'image' => $product->image,
				'name' => $product->name,
				'price' => $product->price,
				'category' => [
					'id' => $product->category->id,
					'name' => $product->category->name,
				]
			]
		], $resource);
	}
}
