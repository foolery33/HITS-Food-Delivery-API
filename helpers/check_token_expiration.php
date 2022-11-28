<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require "libs/vendor/autoload.php";

function isExpiredToken($token)
{
    if ($token) {
        global $Key;
        try {
            JWT::decode($token, new Key($Key, 'HS256'));
            return false;
        } catch (Exception $e) {
            if ($e->getMessage() == "Expired token") {
                return "expired";
            } else {
                return "invalid";
            }
        }
    } else {
        return "invalid";
    }
}