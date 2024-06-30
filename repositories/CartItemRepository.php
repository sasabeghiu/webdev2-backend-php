<?php

namespace Repositories;

use Models\Product;
use Models\CartItem;
use Models\ShoppingCart;
use PDO;
use PDOException;
use Repositories\Repository;

class CartItemRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT cart_item.*, product.name as product_name, shopping_cart.id as shopping_cart_id FROM cart_item INNER JOIN product ON cart_item.product_id = product.id INNER JOIN shopping_cart ON cart_item.cart_id = shopping_cart.id";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $items = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $items[] = $this->rowToProduct($row);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $query = "SELECT cart_item.*, product.name as product_name, shopping_cart.id as shopping_cart_id FROM cart_item INNER JOIN product ON cart_item.product_id = product.id INNER JOIN shopping_cart ON cart_item.cart_id = shopping_cart.id WHERE cart_item.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $item = $this->rowToProduct($row);

            return $item;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function rowToProduct($row)
    {
        $cart_item = new CartItem();
        $cart_item->id = $row['id'];
        $cart_item->cart_id = $row['cart_id'];
        $cart_item->product_id = $row['product_id'];
        $cart_item->quantity = $row['quantity'];

        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['product_name'];

        $shopping_cart = new ShoppingCart();
        $shopping_cart->id = $row['shopping_cart_id'];

        $cart_item->product = $product;
        $cart_item->cart = $shopping_cart;

        return $cart_item;
    }

    function insert($cart_item)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into cart_item (cart_id, product_id, quantity) VALUES (?,?,?)");

            $stmt->execute([$cart_item->cart_id, $cart_item->product_id, $cart_item->quantity]);

            $cart_item->id = $this->connection->lastInsertId();

            return $this->getOne($cart_item->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($cart_item, $id)
    {
        try {
            $cartIdStmt = $this->connection->prepare("SELECT cart_id FROM cart_item WHERE id = :id");
            $cartIdStmt->bindParam(':id', $id);
            $cartIdStmt->execute();
            $cartIdResult = $cartIdStmt->fetch(PDO::FETCH_ASSOC);
            $cart_id = $cartIdResult['cart_id'] ?? null;

            $stmt = $this->connection->prepare("UPDATE cart_item SET quantity = ? WHERE id = ?");
            $stmt->execute([$cart_item->quantity, $id]);

            if ($cart_id) {
                $this->recalculateAndUpdateTotalPrice($cart_id);
            }

            return $this->getOne($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $cartIdStmt = $this->connection->prepare("SELECT cart_id FROM cart_item WHERE id = :id");
            $cartIdStmt->bindParam(':id', $id);
            $cartIdStmt->execute();
            $cartIdResult = $cartIdStmt->fetch(PDO::FETCH_ASSOC);
            $cart_id = $cartIdResult['cart_id'] ?? null;

            $stmt = $this->connection->prepare("DELETE FROM cart_item WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($cart_id) {
                $this->recalculateAndUpdateTotalPrice($cart_id);
            }

            return true;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }

    function recalculateAndUpdateTotalPrice($cart_id)
    {
        // Recalculate the total price
        $totalStmt = $this->connection->prepare("SELECT SUM(p.price * ci.quantity) AS new_total FROM cart_item ci JOIN product p ON ci.product_id = p.id WHERE ci.cart_id = :cart_id");
        $totalStmt->bindParam(':cart_id', $cart_id);
        $totalStmt->execute();
        $newTotalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $newTotal = $newTotalResult['new_total'] ?? 0;

        // Update the total price in the shopping_cart table
        $updateTotalStmt = $this->connection->prepare("UPDATE shopping_cart SET total_price = :new_total WHERE id = :cart_id");
        $updateTotalStmt->bindParam(':new_total', $newTotal);
        $updateTotalStmt->bindParam(':cart_id', $cart_id);
        $updateTotalStmt->execute();
    }

    function addItemToCart($cartId, $productId, $quantity)
    {
        try {
            // Ensure the cart exists
            $stmt = $this->connection->prepare("SELECT id FROM shopping_cart WHERE id = ?");
            $stmt->execute([$cartId]);
            if ($stmt->fetch() === false) {
                echo "Shopping cart does not exist.";
                return null;
            }

            // Check if the product already exists in the cart
            $stmt = $this->connection->prepare("SELECT * FROM cart_item WHERE cart_id = ? AND product_id = ?");
            $stmt->execute([$cartId, $productId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // Update existing cart item
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $this->connection->prepare("UPDATE cart_item SET quantity = ? WHERE id = ?");
                $stmt->execute([$newQuantity, $existingItem['id']]);
            } else {
                // Insert new cart item
                $stmt = $this->connection->prepare("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->execute([$cartId, $productId, $quantity]);
            }

            // Calculate the new total price for the cart
            $stmt = $this->connection->prepare("SELECT SUM(p.price * ci.quantity) AS total FROM cart_item ci JOIN product p ON ci.product_id = p.id WHERE ci.cart_id = ?");
            $stmt->execute([$cartId]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Update the total price in the shopping_cart table
            $stmt = $this->connection->prepare("UPDATE shopping_cart SET total_price = ? WHERE id = ?");
            $stmt->execute([$total, $cartId]);

            return "Item added or updated successfully.";
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getCartItemsCount($id)
    {
        try {
            $query = "SELECT COUNT(*) AS itemCount FROM cart_item WHERE cart_id = (SELECT id FROM shopping_cart WHERE user_id = :id LIMIT 1)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                "success" => true,
                "itemCount" => $result ?? 0
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "errorMessage" => $e->getMessage()
            ];
        }
    }    
}
