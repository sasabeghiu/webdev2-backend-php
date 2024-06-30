<?php

namespace Repositories;

use Models\Role;
use Models\User;
use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            $stmt = $this->connection->prepare('SELECT id, username, password, email, role_id FROM "user" WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = 'SELECT "user".id, "user".username, "user".email, role.id as role_id, role.name as role_name FROM "user" INNER JOIN role ON "user".role_id = role.id';
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $users = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $users[] = $this->rowToProduct($row);
            }

            return $users;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getOne($id)
    {
        try {
            $query = 'SELECT user.id, user.username, user.password, user.email, role.id as role_id, role.name as role_name FROM "user" INNER JOIN role ON "user".role_id = role.id WHERE user.id = :id';
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $user = $this->rowToProduct($row);

            return $user;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    function rowToProduct($row)
    {
        $user = new User();
        $user->id = $row['id'];
        $user->username = $row['username'];
        if (isset($row['password'])) {
            $user->password = $row['password'];
        }
        $user->email = $row['email'];
        $user->role_id = $row['role_id'];
        $role = new Role();
        $role->id = $row['role_id'];
        $role->name = $row['role_name'];

        $user->role = $role;
        return $user;
    }

    function insert($user)
    {
        try {
            $stmt = $this->connection->prepare('INSERT into "user" (username, password, email, role_id) VALUES (?,?,?,?)');

            $password_hash = $this->hashPassword($user->password);
            $stmt->execute([$user->username, $password_hash, $user->email, $user->role_id]);

            $user->id = $this->connection->lastInsertId();

            return $this->getOne($user->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($user, $id)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE "user" SET username = ?, password = ?, email = ?, role_id = ? WHERE id = ?');

            if (!empty($user->password)) {
                $password_hash = $this->hashPassword($user->password);
            } else {
                $currentUserData = $this->getOne($id);
                $password_hash = $currentUserData->password;
            }
            
            $stmt->execute([$user->username, $password_hash, $user->email, $user->role_id, $id]);

            return $this->getOne($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM "user" WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }
}
