<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'category_id' => 'required|exists:categories,id',
			'description' => 'present',
			'image' => 'present|nullable|file|mimes:png,jpg,jpeg',
			'name' => 'required|max:255',
			'price' => 'required|numeric',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'category_id' => __('app.products.category_id'),
			'description' => __('app.products.description'),
			'image' => __('app.products.image'),
			'name' => __('app.products.name'),
			'price' => __('app.products.price'),
		];
	}
}
