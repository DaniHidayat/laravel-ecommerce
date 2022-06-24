<?php

namespace Tests\Feature\Actions;

use App\Actions\UpdateProductAction;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProductActionTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function action_can_update_product()
	{
		$product = Product::factory()->create();

		$specificationData = [
			[
				'name' => 'Spec 1',
				'value' => 'Example 1'
			],
			[
				'name' => 'Spec 2',
				'value' => 'Example 2'
			],
		];

		$productData = [
			'category_id' => Category::factory()->create()->id,
			'name' => 'Example name',
			'price' => 3500000,
			'description' => 'Example description',
			'image' => 'image.jpg',
			'specifications' => $specificationData,
		];

		$action = new UpdateProductAction;
		$action->execute($product, $productData);

		$this->assertDatabaseHas(Product::class, [
			'category_id' => $productData['category_id'],
			'name' => $productData['name'],
			'price' => $productData['price'],
			'description' => $productData['description'],
			'image' => $productData['image'],
		]);

		$this->assertDatabaseCount(ProductSpecification::class, 2);

		$this->assertDatabaseHas(ProductSpecification::class, [
			'name' => $specificationData[0]['name'],
			'value' => $specificationData[0]['value'],
		]);

		$this->assertDatabaseHas(ProductSpecification::class, [
			'name' => $specificationData[1]['name'],
			'value' => $specificationData[1]['value'],
		]);
	}
}
