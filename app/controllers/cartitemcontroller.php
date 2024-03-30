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
}
