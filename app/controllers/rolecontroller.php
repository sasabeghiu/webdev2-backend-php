<?php

namespace Controllers;

use Exception;
use Services\RoleService;

class RoleController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new RoleService();
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

        $roles = $this->service->getAll($offset, $limit);
        $this->respond($roles);
    }

    public function getOne($id)
    {
        $role = $this->service->getOne($id);

        if (!$role) {
            $this->respondWithError(404, "Role not found");
            return;
        }

        $this->respond($role);
    }

    public function create()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $role = $this->createObjectFromPostedJson("Models\\Role");
            $role = $this->service->insert($role);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $role);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $role = $this->createObjectFromPostedJson("Models\\Role");
            $role = $this->service->update($role, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($role);
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
