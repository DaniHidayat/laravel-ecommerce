<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(Category::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$categories = Category::query()->paginate();

		return CategoryResource::collection($categories);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CategoryRequest $request)
	{
		Category::create([
			'name' => $request->name,
			'parent_id' => $request->parent_id,
		]);

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Category $category)
	{
		return new CategoryResource($category);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(CategoryRequest $request, Category $category)
	{
		$category->update([
			'name' => $request->name,
			'parent_id' => $request->parent_id,
		]);

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Category $category)
	{
		try {
			$category->delete();

			return $this->okResponse(__('messages.data_deleted'));
		} catch (\Throwable $th) {
			return $this->serverErrorResponse(__('messages.data_cannot_be_deleted'));
		}
	}
}
