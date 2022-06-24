<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductAction;
use App\Actions\UpdateProductAction;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource(Product::class);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$products = Product::with('category')->paginate();

		return ProductResource::collection($products);
	}

	/**
	 * Add image
	 * Store a newly created resource in storage.
	 */
	public function store(ProductRequest $request, CreateProductAction $createProductAction)
	{
		$createProductAction->execute($request->validated());

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Product $product)
	{
		return new ProductResource($product);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(ProductRequest $request, Product $product, UpdateProductAction $updateProductAction)
	{
		$updateProductAction->execute($product, $request->validated());

		return $this->okResponse(__('messages.data_saved'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Product $product)
	{
		try {
			$product->delete();

			return $this->okResponse(__('messages.data_deleted'));
		} catch (\Throwable $th) {
			return $this->serverErrorResponse(__('messages.data_cannot_be_deleted'));
		}
	}
}
