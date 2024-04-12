<?php

namespace Services;

use Repositories\CartItemRepository;
use Repositories\ShoppingCartRepository;

class CartItemService
{
    private $cartItemRepository;
    private $shoppingCartRepository;

    function __construct()
    {
        $this->cartItemRepository = new CartItemRepository();
        $this->shoppingCartRepository = new ShoppingCartRepository();
    }

    public function getAll($offset = NULL, $limit = NULL)
    {
        return $this->cartItemRepository->getAll($offset, $limit);
    }

    public function getOne($id)
    {
        return $this->cartItemRepository->getOne($id);
    }

    public function insert($item)
    {
        return $this->cartItemRepository->insert($item);
    }

    public function update($item, $id)
    {
        return $this->cartItemRepository->update($item, $id);
    }

    public function delete($item)
    {
        return $this->cartItemRepository->delete($item);
    }

    public function addToCart($user_id, $product_id, $quantity)
    {
        $cart_id = $this->shoppingCartRepository->getCartByUserId($user_id);
        if (!$cart_id) {
            throw new \Exception("Failed to retrieve or create a cart.");
        }

        return $this->cartItemRepository->addItemToCart($cart_id, $product_id, $quantity);
    }
}
