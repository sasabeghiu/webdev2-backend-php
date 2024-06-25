<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class ServiceRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT id, name, description, image FROM service";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Service');
            $services = $stmt->fetchAll();
            return $services;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, name, description, image FROM service WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Service');
            $service = $stmt->fetch();

            if (!$service) {
                return null;
            }

            return $service;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function insert($service)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into service (name, description, image) VALUES (?,?,?)");
            $stmt->execute([$service->name, $service->description, $service->image]);
            $service->id = $this->connection->lastInsertId();
            return $service;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($service, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE service SET name = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$service->name, $service->description, $service->image, $id]);
            return $service;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM service WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
