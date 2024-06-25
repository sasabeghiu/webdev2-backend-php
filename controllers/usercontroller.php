<?php

namespace Controllers;

use Exception;
use Firebase\JWT\JWT;
use Services\UserService;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        // read user data from request body
        $postedUser = $this->createObjectFromPostedJson("Models\\User");

        // get user from db
        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);

        // if the method returned false, the username and/or password were incorrect
        if (!$user) {
            $this->respondWithError(401, "Incorrect username and/or password");
            return;
        }

        // generate jwt
        $tokenResponse = $this->generateJwt($user);

        // return jwt
        $this->respond($tokenResponse);
    }

    function generateJwt($user)
    {
        $secret_key = "random-secret-key";
        $issuer = 'http://localhost';
        $audience = 'http://localhost';
        $issuedAt = time();
        $notBefore = $issuedAt; // Can adjust this as needed
        $expirationTime = $issuedAt + 3600; // Valid for 1 hour

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expirationTime,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "role_id" => $user->role_id
            )
        );

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "id" => $user->id,
                "username" => $user->username,
                "password" => $user->password,
                "role_id" => $user->role_id,
                "email" => $user->email,
                "expireAt" => $expirationTime
            );
    }

    public function getAll()
    {
        if (!$this->checkforJwt([1])) {
            return false;
        }

        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $users = $this->service->getAll($offset, $limit);

        $this->respond($users);
    }

    public function getOne($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $user = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$user) {
            $this->respondWithError(404, "User not found");
            return;
        }

        $this->respond($user);
    }

    public function register()
    {
        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->insert($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(201, $user);
    }

    public function update($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->update($user, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($user);
    }

    public function delete($id)
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondWithCode(204, null);
    }

    public function logout()
    {
        if (!$this->checkforJwt([1, 2])) {
            return false;
        }

        $this->respond([
            "message" => "Successfully logged out",
            "action" => "clearToken"
        ]);
    }
}
