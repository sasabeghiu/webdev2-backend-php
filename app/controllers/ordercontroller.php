<?php

namespace Controllers;

use Exception;
use Services\OrderService;

class OrderController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new OrderService();
    }

    public function create()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $orderData = $this->createObjectFromPostedJson("Models\\Order");
            $order = $this->service->createOrder($orderData);

            if ($order) {
                $this->respondWithCode(201, $order);
            } else {
                $this->respondWithError(400, "Failed to create order");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $order = $this->service->getOne($id);
        if ($order) {
            $this->respond($order);
        } else {
            $this->respondWithError(404, "Order not found");
        }
    }

    public function addOrderItem()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $itemData = $this->createObjectFromPostedJson("Models\\OrderItem");
            $item = $this->service->addOrderItem($itemData);
            if ($item) {
                $this->respondWithCode(201, $item);
            } else {
                $this->respondWithError(400, "Failed to add item to order");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
