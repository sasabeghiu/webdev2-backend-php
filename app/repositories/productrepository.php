<?php

namespace Repositories;

use Models\Category;
use Models\Product;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository
{
    function getAll($name = NULL, $offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT product.*, category.name as category_name FROM product INNER JOIN category ON product.category_id = category.id";

            $params = [];

            if (isset($name)) {
                $query .= " WHERE product.name LIKE :name";
                $params[':name'] = '%' . $name . '%';
            }
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
                $params[':limit'] = $limit;
                $params[':offset'] = $offset;
            }
            $stmt = $this->connection->prepare($query);

            foreach ($params as $param => &$value) {
                if ($param == ':limit' || $param == ':offset') {
                    $stmt->bindParam($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam($param, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();

            $products = [];

            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $query = "SELECT product.*, category.name as category_name FROM product INNER JOIN category ON product.category_id = category.id WHERE product.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $product = $this->rowToProduct($row);

            return $product;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function rowToProduct($row)
    {
        $product = new Product();
        $product->id = $row['id'];
        $product->name = $row['name'];
        $product->price = $row['price'];
        $product->quantity_available = $row['quantity_available'];
        $product->description = $row['description'];
        $product->image = $row['image'];
        $product->category_id = $row['category_id'];
        $category = new Category();
        $category->id = $row['category_id'];
        $category->name = $row['category_name'];

        $product->category = $category;
        return $product;
    }

    function insert($product)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into product (name, price, quantity_available, description, image, category_id) VALUES (?,?,?,?,?,?)");

            $stmt->execute([$product->name, $product->price, $product->quantity_available, $product->description, $product->image, $product->category_id]);

            $product->id = $this->connection->lastInsertId();

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($product, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE product SET name = ?, price = ?, quantity_available = ?, description = ?, image = ?, category_id = ? WHERE id = ?");

            $stmt->execute([$product->name, $product->price, $product->quantity_available, $product->description, $product->image, $product->category_id, $id]);

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM product WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }

    function decrementQuantity($id, $quantity)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE product SET quantity_available = quantity_available - ? WHERE id = ?");

            $stmt->execute([$quantity, $id]);

            return $this->getOne($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
