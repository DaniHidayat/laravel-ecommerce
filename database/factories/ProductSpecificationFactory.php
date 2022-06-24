<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpecification>
 */
class ProductSpecificationFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'product_id' => Product::factory(),
			'name' => "Spec {$this->faker->word}",
			'value' => "Val {$this->faker->word}",
		];
	}
}
