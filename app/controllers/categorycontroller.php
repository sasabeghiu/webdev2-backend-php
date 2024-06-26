<?php

namespace Controllers;

use Exception;
use Services\CategoryService;

class CategoryController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new CategoryService();
    }

    public function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $categories = $this->service->getAll($offset, $limit);
        $this->respond($categories);
    }

    public function getOne($id)
    {
        $category = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$category) {
            $this->respondWithError(404, "Category not found");
            return;
        }

        $this->respond($category);
    }

    public function create()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->insert($category);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $category);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->update($category, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($category);
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
