<?php

namespace Models;

class User
{
    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public string $role_id;
    public Role $role;
}
