<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the products endpoint
$router->get('/products', 'ProductController@getAll');
$router->get('/products/(\d+)', 'ProductController@getOne');
$router->post('/products', 'ProductController@create');
$router->put('/products/(\d+)', 'ProductController@update');
$router->delete('/products/(\d+)', 'ProductController@delete');

// routes for the categories endpoint
$router->get('/categories', 'CategoryController@getAll');
$router->get('/categories/(\d+)', 'CategoryController@getOne');
$router->post('/categories', 'CategoryController@create');
$router->put('/categories/(\d+)', 'CategoryController@update');
$router->delete('/categories/(\d+)', 'CategoryController@delete');

// routes for the user roles endpoint
$router->get('/roles', 'RoleController@getAll');
$router->get('/roles/(\d+)', 'RoleController@getOne');
$router->post('/roles', 'RoleController@create');
$router->put('/roles/(\d+)', 'RoleController@update');
$router->delete('/roles/(\d+)', 'RoleController@delete');

// routes for the services endpoint
$router->get('/services', 'ServiceController@getAll');
$router->get('/services/(\d+)', 'ServiceController@getOne');
$router->post('/services', 'ServiceController@create');
$router->put('/services/(\d+)', 'ServiceController@update');
$router->delete('/services/(\d+)', 'ServiceController@delete');

// routes for the shopping carts endpoint
$router->get('/shoppingcarts', 'ShoppingCartController@getAll');
$router->get('/shoppingcarts/(\d+)', 'ShoppingCartController@getOne');
$router->get('/shoppingcarts/user_(\d+)', 'ShoppingCartController@getCartItemsByUserId');
$router->post('/shoppingcarts', 'ShoppingCartController@create');
$router->put('/shoppingcarts/(\d+)', 'ShoppingCartController@update');
$router->delete('/shoppingcarts/(\d+)', 'ShoppingCartController@delete');

// routes for the shopping cart items endpoint
$router->get('/cartitems', 'CartItemController@getAll');
$router->get('/cartitems/(\d+)', 'CartItemController@getOne');
$router->post('/cartitems', 'CartItemController@create');
$router->put('/cartitems/(\d+)', 'CartItemController@update');
$router->delete('/cartitems/(\d+)', 'CartItemController@delete');
$router->post('/cartitems/addToCart', 'CartItemController@addToCart');
$router->get('/cartitems/user/(\d+)/count', 'CartItemController@getCartItemsCount');

// routes for the users endpoint
$router->get('/users', 'UserController@getAll');
$router->get('/users/(\d+)', 'UserController@getOne');
$router->post('/users/register', 'UserController@register'); //
$router->post('/users/login', 'UserController@login'); //
$router->post('/users/logout', 'UserController@logout'); //
$router->put('/users/(\d+)', 'UserController@update'); //
$router->delete('/users/(\d+)', 'UserController@delete'); //

// routes for the orders endpoint
$router->get('/orders/(\d+)', 'OrderController@getOne');
$router->post('/orders', 'OrderController@create');
$router->post('/orders/items', 'OrderController@addOrderItem');

// Run it!
$router->run();
