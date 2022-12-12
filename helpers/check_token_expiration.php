<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function isGoodToken($token): bool
{
    if ($token) {
        global $Key;
        try {
            global $Link;
            $searchResult = $Link->query("SELECT * FROM token_blacklist WHERE token = '$token'")->fetch_assoc();
            if ($searchResult) {
                setHTTPStatus("403", "User with provided token has already been logged out");
                return false;
            }
            $decode = JWT::decode($token, new Key($Key, 'HS256'));
            return true;
        } catch (Exception $e) {
            if ($e->getMessage() == "Expired token") {
                setHTTPStatus("401", "Your token is expired");
            } else {
                setHTTPStatus("401", "Your token is not valid");
            }
            return false;
        }
    } else {
        setHTTPStatus("401", "Your token is not valid");
        return false;
    }
}