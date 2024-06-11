<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class ShippingInfoRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT shipping_info.id, user_id, full_name, address, city, postal_code, country, user.email, phone_number FROM shipping_info INNER JOIN user ON shipping_info.user_id = user.id";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ShippingInfo');
            $shipping_info = $stmt->fetchAll();
            return $shipping_info;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT shipping_info.id, user_id, full_name, address, city, postal_code, country, shipping_info.email, phone_number FROM shipping_info WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ShippingInfo');
            $shipping_info = $stmt->fetch();

            if (!$shipping_info) {
                return null;
            }

            return $shipping_info;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function getOneByUserId($user_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT shipping_info.id, user_id, full_name, address, city, postal_code, country, shipping_info.email, phone_number FROM shipping_info WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ShippingInfo');
            $shipping_info = $stmt->fetch();

            if (!$shipping_info) {
                return null;
            }

            return $shipping_info;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function insert($shipping_info)
    {
        try {
            $existingShippingInfo = $this->getOneByUserId($shipping_info->user_id);
            if ($existingShippingInfo) {
                return $this->update($shipping_info, $existingShippingInfo->id);
            }

            $stmt = $this->connection->prepare("INSERT INTO shipping_info(user_id, full_name, address, city, postal_code, country, email, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([$shipping_info->user_id, $shipping_info->full_name, $shipping_info->address, $shipping_info->city, $shipping_info->postal_code, $shipping_info->country, $shipping_info->email, $shipping_info->phone_number]);

            $shipping_info->id = $this->connection->lastInsertId();

            return $shipping_info;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($shipping_info, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE shipping_info SET user_id = ?, full_name = ?, address = ?, city = ?, postal_code = ?, country = ?, email = ?, phone_number = ? WHERE user_id = ?");
            $stmt->execute([$shipping_info->user_id, $shipping_info->full_name, $shipping_info->address, $shipping_info->city, $shipping_info->postal_code, $shipping_info->country, $shipping_info->email, $shipping_info->phone_number, $id]);
            return $this->getOneByUserId($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM shipping_info WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
