<?php

namespace Models;

class Product
{
    public int $id;
    public string $name;
    public string $price;
    public int $quantity_available;
    public string $description;
    public string $image;
    public string $category_id;
    public Category $category;
}
