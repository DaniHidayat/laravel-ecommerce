<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
	/**
	 * Json response with http status 200 OK
	 */
	public function okResponse(string $message = 'OK')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_OK);
	}

	/**
	 * Json response with http status 201 Created
	 */
	public function createdResponse(string $message = 'Created')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_CREATED);
	}

	/**
	 * Json response with http status 204 No Content
	 */
	public function noContentResponse(string $message = 'No Content')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_NO_CONTENT);
	}

	/**
	 * Json response with http status 401 Unauthorized
	 */
	public function unauthorizedResponse(string $message = 'Unauthorized')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_UNAUTHORIZED);
	}

	/**
	 * Json response with http status 403 Forbidden
	 */
	public function forbiddenResponse(string $message = 'Forbidden')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_FORBIDDEN);
	}

	/**
	 * Json response with http status 404 Not Found
	 */
	public function notFoundResponse(string $message = 'Not Found')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_NOT_FOUND);
	}

	/**
	 * Json response with http status 422 Unprocessable Entity
	 */
	public function unprocessableEntityResponse(string $message = 'Unprocessable Entity')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * Json response with http status 500 Internal Server Error
	 */
	public function serverErrorResponse(string $message = 'Internal Server Error')
	{
		return response()->json([
			'message' => $message
		], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
