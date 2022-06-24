<?php

namespace Tests\Feature\Actions;

use App\Actions\CreateProductAction;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductActionTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function action_can_create_product()
	{
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

		$action = new CreateProductAction;
		$action->execute($productData);

		$this->assertDatabaseHas(Product::class, [
			'category_id' => $productData['category_id'],
			'name' => $productData['name'],
			'price' => $productData['price'],
			'description' => $productData['description'],
			'image' => $productData['image'],
		]);

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
