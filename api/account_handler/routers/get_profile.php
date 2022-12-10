<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require "libs/vendor/autoload.php";

function getProfile($requestData)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if(isExpiredToken($token)) {
        return;
    }

    global $Key;
    $decoded = (array)JWT::decode($token, new Key($Key, 'HS256'));
    echo json_encode($decoded['data']);
}