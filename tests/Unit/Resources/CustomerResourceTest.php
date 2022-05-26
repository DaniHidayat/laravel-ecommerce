<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Carbon\Carbon;
use Tests\TestCase;

class CustomerResourceTest extends TestCase
{
	/** @test */
	public function can_get_customer_resource_in_correct_format()
	{
		$customer = Customer::factory()->make([
			'id' => 1,
			'name' => 'John Doe',
			'email' => 'john.doe@example.test',
			'created_at' => Carbon::parse('25-05-2020'),
		]);

		$resource = (new CustomerResource($customer))
			->response()
			->getData(true);

		$this->assertEquals([
			'data' => [
				'id' => 1,
				'name' => 'John Doe',
				'email' => 'john.doe@example.test',
				'joined_at' => '25-05-2020',
			]
		], $resource);
	}
}
