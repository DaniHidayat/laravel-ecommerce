<?php

namespace App\Enums;

class PermissionEnum
{
	// Categories
	const GET_ALL_CATEGORIES = 'Get all categories';
	const GET_SELECTED_CATEGORY = 'Get selected category';
	const ADD_CATEGORY = 'Add category';
	const UPDATE_CATEGORY = 'Update category';
	const DELETE_CATEGORY = 'Delete category';
	// const DESTROY_CATEGORY = 'Delete category permanently';

	// Customers
	const GET_ALL_CUSTOMERS = 'Get all customers';
	const GET_SELECTED_CUSTOMER = 'Get selected customer';
	const UPDATE_CUSTOMER = 'Update customer';
	const DELETE_CUSTOMER = 'Delete customer';

	// Users
	const GET_ALL_USERS = 'Get all users';
	const GET_SELECTED_USER = 'Get selected user';
	const ADD_USER = 'Add user';
	const UPDATE_USER = 'Update user';
	const DELETE_USER = 'Delete user';

	// Products
	const GET_ALL_PRODUCTS = 'Get all products';
	const GET_SELECTED_PRODUCT = 'Get selected product';
	const ADD_PRODUCT = 'Add product';
	const UPDATE_PRODUCT = 'Update product';
	const DELETE_PRODUCT = 'Delete product';
}
