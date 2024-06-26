<?php

namespace Repositories;

use Models\User;
use Models\ShoppingCart;
use PDO;
use PDOException;
use Repositories\Repository;

class ShoppingCartRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT shopping_cart.id, user_id, created_at, updated_at, total_price, user.username as user_username FROM shopping_cart INNER JOIN user ON shopping_cart.user_id = user.id";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $carts = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $carts[] = $this->rowToProduct($row);
            }

            return $carts;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $query = "SELECT shopping_cart.id, user_id, created_at, updated_at, total_price, user.username as user_username FROM shopping_cart INNER JOIN user ON shopping_cart.user_id = user.id WHERE shopping_cart.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $cart = $this->rowToProduct($row);

            return $cart;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getCartByUserId($id)
    {
        try {
            $query = "SELECT shopping_cart.id, user_id, created_at, updated_at, total_price, user.username as user_username FROM shopping_cart INNER JOIN user ON shopping_cart.user_id = user.id WHERE user.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                $stmt = $this->connection->prepare("INSERT INTO shopping_cart (user_id, created_at, updated_at, total_price) VALUES (?, NOW(), NOW(), 0)");
                $stmt->execute([$id]);
                return $this->connection->lastInsertId();
            }

            $cart = $row['id'];

            return $cart;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getCartItemsByUserId($id)
    {
        try {
            $query = "SELECT ci.*, p.name as product_name, p.price as product_price, p.image as product_image
            FROM cart_item ci
            JOIN shopping_cart sc ON ci.cart_id = sc.id
            JOIN product p ON ci.product_id = p.id
            WHERE sc.user_id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetchAll();

            if (!$row) {
                return null;
            }

            return $row;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function rowToProduct($row)
    {
        $shopping_cart = new ShoppingCart();
        $shopping_cart->id = $row['id'];
        $shopping_cart->user_id = $row['user_id'];
        $shopping_cart->created_at = $row['created_at'];
        $shopping_cart->updated_at = $row['updated_at'];
        $shopping_cart->total_price = $row['total_price'];
        $user = new User();
        $user->id = $row['user_id'];
        $user->username = $row['user_username'];

        $shopping_cart->user = $user;
        return $shopping_cart;
    }

    function insert($shopping_cart)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into shopping_cart(user_id, created_at, updated_at, total_price) VALUES (?, NOW(), NOW(),?)");

            $stmt->execute([$shopping_cart->user_id, $shopping_cart->total_price]);

            $shopping_cart->id = $this->connection->lastInsertId();

            return $this->getOne($shopping_cart->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($shopping_cart, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE shopping_cart SET user_id = ?, created_at = ?, updated_at = NOW(), total_price = ? WHERE id = ?");

            $stmt->execute([$shopping_cart->user_id, $shopping_cart->created_at, $shopping_cart->total_price, $id]);

            return $this->getOne($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $item_stmt = $this->connection->prepare("DELETE FROM cart_item WHERE cart_id = :id");
            $item_stmt->bindParam(':id', $id);
            $item_stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM shopping_cart WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }
}
