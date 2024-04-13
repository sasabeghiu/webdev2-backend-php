<?php

namespace Controllers;

use Exception;
use Services\CartItemService;

class CartItemController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new CartItemService();
    }

    public function getAll()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $items = $this->service->getAll($offset, $limit);

        $this->respond($items);
    }

    public function getOne($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $item = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$item) {
            $this->respondWithError(404, "Shopping Cart Item not found");
            return;
        }

        $this->respond($item);
    }

    public function create()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $item = $this->createObjectFromPostedJson("Models\\CartItem");
            $item = $this->service->insert($item);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $item);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $item = $this->createObjectFromPostedJson("Models\\CartItem");
            $item = $this->service->update($item, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($item);
    }

    public function delete($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(204, null);
    }
    /**
     * Adds a product to the shopping cart.
     */
    public function addToCart()
    {
        $user_id = $this->checkForJwt([1, 2]);  // Assume 1, 2 are valid roles
        if (!$user_id) {
            return;  // Proper response already handled in verifyJwtAndGetUserId
        }

        // Get JSON data from the request body
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->respondWithError(400, 'Bad Request: Invalid JSON data');
            return;
        }

        $cart_id = $data['cart_id'] ?? null;
        $product_id = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        // Validate required parameters
        if (is_null($cart_id) || is_null($product_id) || is_null($quantity)) {
            $this->respondWithError(400, 'Bad Request: Missing required parameters');
            return;
        }

        try {
            // Attempt to add item to cart
            $this->service->addToCart($user_id, $product_id, $quantity);
            $this->respondWithCode(204, null); // No Content response
        } catch (\Exception $e) {
            // Internal Server Error
            $this->respondWithError(500, 'Internal Server Error: ' . $e->getMessage());
        }
    }

    public function getCartItemsCount($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $item = $this->service->getCartItemsCount($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$item) {
            $this->respondWithError(404, "Shopping Cart Item not found");
            return;
        }

        $this->respond($item);
    }
}
