<?php

namespace Services;

use Repositories\OrderRepository;
use Repositories\CartItemRepository;
use Repositories\ProductRepository;

class OrderService
{
    private $orderrepository;
    private $productRepository;


    function __construct()
    {
        $this->orderrepository = new OrderRepository();
        $this->productRepository = new ProductRepository();
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
        $this->productRepository->decrementQuantity($item->product_id, $item->quantity);
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
