<?php

namespace Services;

use Repositories\ShoppingCartRepository;

class ShoppingCartService
{
    private $repository;

    function __construct()
    {
        $this->repository = new ShoppingCartRepository();
    }

    public function getAll($offset = NULL, $limit = NULL)
    {
        return $this->repository->getAll($offset, $limit);
    }

    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }

    public function getCartByUserId($id)
    {
        return $this->repository->getCartByUserId($id);
    }

    public function getCartItemsByUserId($id)
    {
        return $this->repository->getCartItemsByUserId($id);
    }

    public function insert($item)
    {
        return $this->repository->insert($item);
    }

    public function update($item, $id)
    {
        return $this->repository->update($item, $id);
    }

    public function delete($item)
    {
        return $this->repository->delete($item);
    }
}
