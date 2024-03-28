<?php

namespace Controllers;
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
                "email" => $user->email
            )
        );

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "username" => $user->username,
                "passwprd" => $user->password,
                "expireAt" => $expirationTime
            );
    }
}
