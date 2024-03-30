<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class RoleRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT id, name FROM role";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Role');
            $roles = $stmt->fetchAll();
            return $roles;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, name FROM role WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Role');
            $role = $stmt->fetch();
            return $role;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function insert($role)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into role (name) VALUES (?)");
            $stmt->execute([$role->name]);
            $role->id = $this->connection->lastInsertId();
            return $role;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($role, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE role SET name = ? WHERE id = ?");
            $stmt->execute([$role->name, $id]);
            return $role;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM role WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
