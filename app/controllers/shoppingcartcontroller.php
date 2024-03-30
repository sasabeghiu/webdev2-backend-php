<?php

namespace Controllers;

use Exception;
use Services\ShoppingCartService;

class ShoppingCartController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new ShoppingCartService();
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

        $carts = $this->service->getAll($offset, $limit);

        $this->respond($carts);
    }

    public function getOne($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $cart = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$cart) {
            $this->respondWithError(404, "Shopping Cart not found");
            return;
        }

        $this->respond($cart);
    }

    public function create()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $cart = $this->createObjectFromPostedJson("Models\\ShoppingCart");
            $cart = $this->service->insert($cart);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $cart);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $cart = $this->createObjectFromPostedJson("Models\\ShoppingCart");
            $cart = $this->service->update($cart, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($cart);
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
}
