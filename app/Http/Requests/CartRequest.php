<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
		// PUT or PATCH method
		if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
			return [
				'product_id' => 'required|exists:cart_has_products,product_id',
				'quantity' => 'required|integer|min:1'
			];
		}

		// DELETE method
		if (request()->isMethod('DELETE')) {
			return [
				'product_id' => 'required|exists:cart_has_products,product_id',
			];
		}

		// POST method
		return [
			'product_id' => 'required|exists:products,id',
			'quantity' => 'required|integer|min:1'
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
			'product_id' => __('app.carts.product_id'),
			'quantity' => __('app.carts.quantity'),
		];
	}
}
