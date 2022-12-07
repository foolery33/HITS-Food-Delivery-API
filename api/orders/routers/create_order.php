<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";
include_once "api/account/helpers/generators/id_generator.php";
include_once "helpers/get_current_date_and_time.php";
include_once "helpers/valid_time_format.php";
include_once "helpers/time_to_int.php";
include_once "api/orders/helpers/get_order_price.php";
include_once "api/orders/helpers/insert_order_id.php";

function createOrder($requestData)
{
    global $Link;
    $token = substr(getallheaders()['Authorization'], 7);

    $deliveryTime = $requestData->body->deliveryTime;
    $address = $requestData->body->address;
    if (!isset($deliveryTime) && !isset($address)) {
        setHTTPStatus("400", array("deliveryTime" => "'deliveryTime' field is required", "address" => "'address' field is required"));
    } elseif (!isset($deliveryTime)) {
        setHTTPStatus("400", "'deliveryTime' field is required");
    } elseif (!isset($address)) {
        setHTTPStatus("400", "'address' field is required");
    }

    if ($token) {
        global $Key;
        try {
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            /* Проверяем, пуста ли корзина */
            $dishesInCart = $Link->query("SELECT dish_id, amount FROM dish_basket WHERE user_id = '$userID' AND order_id IS NULL")->fetch_all();
            // Корзина не пуста
            if ($dishesInCart) {
                // Создаём новый заказ
                $orderID = generateID();
                $currentTime = getCurrentDateAndTime();
                //echo $orderID . " " . $userID . " " . $deliveryTime . " " . $currentTime . " ";
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
                        echo timeToInt($currentTime) . "\n" . timeToInt($deliveryTime);
                        setHTTPStatus("400", "Invalid delivery time. Delivery time must be more than current datetime on 60 minutes");
                    }
                } else {
                    setHTTPStatus("400", "Provided 'deliveryTime' data doesn't match to required format");
                }
            } else {
                setHTTPStatus("400", "Empty basket for user with id=$userID");
            }
        } catch (Exception $e) {
            if ($e->getMessage() == "Expired token") {
                setHTTPStatus("401", "Your token is expired");
            } else {
                setHTTPStatus("401", "Your token is not valid");
            }
        }
    } else {
        setHTTPStatus("401", "Your token is not valid");
    }
}