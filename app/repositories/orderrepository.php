<?php

namespace Repositories;

use Models\Order;
use Models\OrderItem;
use Models\User;
use PDO;
use PDOException;
use Repositories\Repository;


class OrderRepository extends Repository
{
    function getOne($id)
    {
        try {
            $query = "SELECT o.*, oi.id AS item_id, oi.product_id, oi.quantity, oi.price FROM `order` o
            LEFT JOIN order_item oi ON o.id = oi.order_id
            WHERE o.id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$id]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $order = $this->rowToOrder($row);

            if ($row['item_id']) {
                $order->items[] = $this->rowToOrderItem($row);
            }

            return $order;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getAll()
    {
        try {
            $query = "SELECT o.*, oi.id AS item_id, oi.product_id, oi.quantity, oi.price FROM `order` o
            LEFT JOIN order_item oi ON o.id = oi.order_id
            ORDER BY created_at DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $orders = [];
            $lastOrderId = null;
            $order = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($lastOrderId != $row['id']) {
                    if ($order) {
                        $orders[] = $order;
                    }

                    $order = $this->rowToOrder($row);
                    $lastOrderId = $row['id'];
                }

                if ($row['item_id']) {
                    $order->items[] = $this->rowToOrderItem($row);
                }
            }

            if ($order) {
                $orders[] = $order;
            }

            return $orders;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getAllByUserId($user_id)
    {
        try {
            $query = "SELECT o.*, oi.id AS item_id, oi.product_id, oi.quantity, oi.price FROM `order` o
            LEFT JOIN order_item oi ON o.id = oi.order_id
            WHERE o.user_id = ?
            ORDER BY created_at DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$user_id]);

            $orders = [];
            $lastOrderId = null;
            $order = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($lastOrderId != $row['id']) {
                    if ($order) {
                        $orders[] = $order;
                    }

                    $order = $this->rowToOrder($row);
                    $lastOrderId = $row['id'];
                }

                if ($row['item_id']) {
                    $order->items[] = $this->rowToOrderItem($row);
                }
            }

            if ($order) {
                $orders[] = $order;
            }

            return $orders;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function rowToOrder($row)
    {
        $order = new Order();
        $order->id = $row['id'];
        $order->user_id = $row['user_id'];
        $order->total = $row['total'];
        $order->status = $row['status'];
        $order->created_at = $row['created_at'];
        $order->updated_at = $row['updated_at'];

        return $order;
    }

    function rowToOrderItem($row)
    {
        $item = new OrderItem();
        $item->id = $row['item_id'];
        $item->order_id = $row['id'];
        $item->product_id = $row['product_id'];
        $item->quantity = $row['quantity'];
        $item->price = $row['price'];

        return $item;
    }

    public function createOrder($order)
    {
        try {
            $query = "INSERT INTO developmentdb.order (user_id, total, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $this->connection->prepare($query);

            $stmt->execute([$order->user_id, $order->total, $order->status]);

            $order->id = $this->connection->lastInsertId();

            return $this->getOne($order->id);
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function addOrderItem($item)
    {
        try {
            $query = "INSERT INTO order_item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);

            $stmt->execute([$item->order_id, $item->product_id, $item->quantity, $item->price]);

            $item->id = $this->connection->lastInsertId();
            return $item->id;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
}
