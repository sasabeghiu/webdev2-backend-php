<?php

namespace Models;

class Order
{
    public int $id;
    public int $user_id;
    public string $total;
    public string $status;
    public string $created_at;
    public string $updated_at;
}
