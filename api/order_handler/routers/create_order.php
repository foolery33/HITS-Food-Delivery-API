<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";
include_once "api/account_handler/helpers/generators/id_generator.php";
include_once "helpers/get_current_date_and_time.php";
include_once "helpers/valid_time_format.php";
include_once "helpers/time_to_int.php";
include_once "api/order_handler/helpers/get_order_price.php";
include_once "api/order_handler/helpers/insert_order_id.php";

function createOrder($requestData)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $deliveryTime = $requestData->body->deliveryTime;
    $address = $requestData->body->address;

    $errors = [];
    if(strlen($deliveryTime) == 0) {
        $errors['DeliveryTime'] = ['The DeliveryTime field is required'];
    }
    if(strlen($address) == 0) {
        $errors['Address'] = ['The Address field is required'];
    }
    if(sizeof($errors) != 0) {
        setHTTPStatus("400", $errors);
        return;
    }

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;
    /* Проверяем, пуста ли корзина */
    $dishesInCart = $Link->query("SELECT dish_id, amount FROM dish_basket WHERE user_id = '$userID' AND order_id IS NULL")->fetch_all();
    // Корзина не пуста
    if ($dishesInCart) {
        // Создаём новый заказ
        $orderID = generateID();
        $currentTime = getCurrentDateAndTime();
        if (isValidTimeFormat($deliveryTime)) {
            if (timeToInt($currentTime) + 3600 < timeToInt($deliveryTime)) {
                $orderPrice = getOrderPrice($dishesInCart);
                $insertResult = $Link->query("INSERT INTO orders(order_id, user_id, deliveryTime, orderTime, status, price, address) VALUES ('$orderID', '$userID', '$deliveryTime', '$currentTime','InProcess', '$orderPrice', '$address')");
                if (!$insertResult) {
                    setHTTPStatus("500", "Database error: $Link->error");
                } else {
                    insertOrderID($orderID, $userID, $dishesInCart);
                }
            } else {
                setHTTPStatus("400", "Invalid delivery time. Delivery time must be more than current datetime on 60 minutes");
            }
        } else {
            setHTTPStatus("400", "Provided 'deliveryTime' data doesn't match to required format");
        }
    } else {
        setHTTPStatus("400", "Empty basket for user with id = '$userID'");
    }
}