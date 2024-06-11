<?php

namespace Controllers;

use Exception;
use Services\ShippingInfoService;

class ShippingInfoController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new ShippingInfoService();
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

        $shipping_info = $this->service->getAll($offset, $limit);

        $this->respond($shipping_info);
    }

    public function getOne($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $shipping_info = $this->service->getOne($id);

        if (!$shipping_info) {
            $this->respondWithError(404, "Shipping Info not found");
            return;
        }

        $this->respond($shipping_info);
    }

    public function getOneByUserId($user_id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $shipping_info = $this->service->getOneByUserId($user_id);

        if (!$shipping_info) {
            $this->respondWithError(404, "Shipping Info not found");
            return;
        }

        $this->respond($shipping_info);
    }

    public function create()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $shipping_info = $this->createObjectFromPostedJson("Models\\ShippingInfo");
            $shipping_info = $this->service->insert($shipping_info);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $shipping_info);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $shipping_info = $this->createObjectFromPostedJson("Models\\ShippingInfo");
            $shipping_info = $this->service->update($shipping_info, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($shipping_info);
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
