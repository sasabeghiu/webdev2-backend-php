<?php

namespace Models;

class ShippingInfo
{
    public int $id;
    public string $user_id;
    public User $user;
    public string $full_name;
    public string $address;
    public string $city;
    public string $postal_code;
    public string $country;
    public string $email;
    public string $phone_number;
}
