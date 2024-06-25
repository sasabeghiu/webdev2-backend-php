<?php

namespace Controllers;

use Exception;
use Services\ServiceService;

class ServiceController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new ServiceService();
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

        $services = $this->service->getAll($offset, $limit);
        $this->respond($services);
    }

    public function getOne($id)
    {
        $service = $this->service->getOne($id);

        if (!$service) {
            $this->respondWithError(404, "Service not found");
            return;
        }

        $this->respond($service);
    }

    public function create()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $service = $this->createObjectFromPostedJson("Models\\Service");
            $service = $this->service->insert($service);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $service);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        try {
            $service = $this->createObjectFromPostedJson("Models\\Service");
            $service = $this->service->update($service, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($service);
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
