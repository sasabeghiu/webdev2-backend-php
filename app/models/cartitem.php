<?php

namespace Models;

class CartItem
{
    public int $id;
    public string $cart_id;
    public ShoppingCart $cart;
    public string $product_id;
    public Product $product;
    public int $quantity;
}
