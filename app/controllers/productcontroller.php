<?php

namespace Controllers;

use Exception;
use Services\ProductService;

class ProductController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll()
    {
        $name = NULL;
        $category = NULL;
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["name"])) {
            $name = $_GET["name"] ?? NULL;
        }
        if (isset($_GET["category"]) && is_numeric($_GET["category"])) {
            $category = $_GET["category"];
        }
        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $products = $this->service->getAll($name, $category, $offset, $limit);

        $this->respond($products);
    }

    public function getOne($id)
    {
        $product = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$product) {
            $this->respondWithError(404, "Product not found");
            return;
        }

        $this->respond($product);
    }

    public function create()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");
            $product = $this->service->insert($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $product);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");
            $product = $this->service->update($product, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function delete($id)
    {
        if (!$this->checkforJwt([1])) {
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
