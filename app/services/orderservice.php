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

    public function getAll()
    {
        return $this->orderrepository->getAll();
    }

    public function getAllByUserId($user_id)
    {
        return $this->orderrepository->getAllByUserId($user_id);
    }

    public function createOrder($order)
    {
        return $this->orderrepository->createOrder($order);
    }

    public function addOrderItem($item)
    {
        return $this->orderrepository->addOrderItem($item);
    }

    public function update($order, $id)
    {
        return $this->orderrepository->update($order, $id);
    }

    public function delete($id)
    {
        return $this->orderrepository->delete($id);
    }
}
