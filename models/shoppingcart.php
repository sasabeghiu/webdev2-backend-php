<?php

namespace Models;

class ShoppingCart
{
    public int $id;
    public string $user_id;
    public User $user;
    public string $created_at;
    public string $updated_at;
    public string $total_price;
}
