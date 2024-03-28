# Docker setup that runs a REST api
This repository provides a helper project for a Vue frontend assignment.

It contains:
* NGINX webserver
* PHP FastCGI Process Manager with PDO MySQL support
* MariaDB (GPL MySQL fork)
* PHPMyAdmin

## Installation

1. Install Docker Desktop on Windows or Mac, or Docker Engine on Linux.
1. Clone the project

## Usage

In a terminal, run:
```bash
docker-compose up
```

NGINX will now serve files in the app/public folder. Visit localhost in your browser to check.
PHPMyAdmin is accessible on localhost:8080

If you want to stop the containers, press Ctrl+C. 
Or run:
```bash
docker-compose down
```

# Web Development 2 Assignment
This repository serves as a backend REST API in php for the web development 2 assignment.

1.	Users
GET /api/users – retrieve a list of all users.
GET /api/users/{user_id} – retrieve a specific user.
POST /api/users/register – register a user.
POST /api/users/login – login an existing user.
POST /api/users/logout – logout current user.
PUT /api/users/{user_id} – update user’s information (for both users and admins).
DELETE /api/users/{user_id} – delete a user account (user can delete his own account, while admin can delete any account).

2.	Products
GET /api/products – retrieve a list of all products.
GET /api/ products /{product_id} – retrieve a specific product.
POST /api/ products – add a new product (admin only).
PUT /api/ products /{product_id} – update details of existing order (admin only).
DELETE /api/ products /{product_id} – delete an existing product (admin only).

3.	 Shopping Carts
GET /api/carts – retrieve a list of all shopping carts (admin only).
GET /api/carts/{user_id} – retrieve the shopping cart of a specific user.
POST /api/carts/add – add a product to the existing cart.
PUT /api/carts/update – update the quantity of a product inside the cart.
DELETE /api/carts/remove – remove a product from user’s shopping cart.
POST /api/carts/checkout – process the user’s order and complete checkout.

## Existing user
username: username
password: password