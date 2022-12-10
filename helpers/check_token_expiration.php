<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require "libs/vendor/autoload.php";

function isExpiredToken($token): bool
{
    if ($token) {
        global $Key;
        try {
            JWT::decode($token, new Key($Key, 'HS256'));
            return false;
        } catch (Exception $e) {
            if ($e->getMessage() == "Expired token") {
                setHTTPStatus("401", "Your token is expired");
            } else {
                setHTTPStatus("401", "Your token is not valid");
            }
            return true;
        }
    } else {
        setHTTPStatus("401", "Your token is not valid");
        return true;
    }
}