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
            $query = "SELECT * FROM `order` WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$id]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $order = $this->rowToProduct($row);

            return $order;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function rowToProduct($row)
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
