<?php

namespace Services;

use Repositories\OrderRepository;
use Repositories\CartItemRepository;

class OrderService
{
    private $orderrepository;

    function __construct()
    {
        $this->orderrepository = new OrderRepository();
    }

    public function getOne($id)
    {
        return $this->orderrepository->getOne($id);
    }

    public function createOrder($order)
    {
        return $this->orderrepository->createOrder($order);
    }

    public function addOrderItem($item)
    {
        return $this->orderrepository->addOrderItem($item);
    }
}
