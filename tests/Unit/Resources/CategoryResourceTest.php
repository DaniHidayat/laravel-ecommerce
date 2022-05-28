<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
	/** @test */
	public function can_get_category_resource_in_correct_format()
	{
		$category = Category::factory()->make(['id' => 1]);

		$resource = (new CategoryResource($category))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => $category->id,
				'name' => $category->name,
				'parent_id' => $category->parent_id,
			]
		], $resource);
	}
}
