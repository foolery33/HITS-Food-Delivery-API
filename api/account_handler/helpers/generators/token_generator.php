<?php

include_once "libs/vendor/firebase/php-jwt/src/BeforeValidException.php";
include_once "libs/vendor/firebase/php-jwt/src/ExpiredException.php";
include_once "libs/vendor/firebase/php-jwt/src/SignatureInvalidException.php";
include_once "libs/vendor/firebase/php-jwt/src/JWT.php";

use Firebase\JWT\JWT;

/*
 * Функция, позволяющая сгенерировать JWT-Token, который выдаётся пользователю при регистрации и авторизации
 */

function generateUserToken($user): string
{

    global $Key;
    // Показ сообщений об ошибках
    error_reporting(E_ALL);

    // Установим часовой пояс по умолчанию
    date_default_timezone_set("Europe/Moscow");

    // Переменные, используемые для JWT
    $iss = "localhost";
    $aud = "localhost";

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "exp" => time() + 10000,
        "data" => array(
            "id" => $user['user_id'],
            "fullName" => $user['fullName'],
            "birthDate" => $user['birthDate'],
            "gender" => $user['gender'],
            "address" => $user['address'],
            "email" => $user['email'],
            "phoneNumber" => $user['phoneNumber'],
        )
    );

    return JWT::encode($token, $Key, 'HS256');
}
