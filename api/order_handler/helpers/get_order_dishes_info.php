<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function getOrderDishesInfo($dishesInOrder, $orderID)
{
    global $Link;

    $token = substr(getallheaders()['Authorization'], 7);
    if ($token) {
        global $Key;
        try {
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $order = [];
            foreach ($dishesInOrder as $value) {
                $currentDishAmount = $Link->query("SELECT amount FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$value[0]' AND order_id = '$orderID'")->fetch_assoc()['amount'];
                $currentDish = $Link->query("SELECT name, price, image, dish_id FROM dish WHERE dish_id = '$value[0]'")->fetch_assoc();
                $order[sizeof($order)] = array("id" => $value[0], "name" => $currentDish['name'], "price" => (float)$currentDish['price'], "totalPrice" => (float)$currentDish['price'] * (int)$currentDishAmount, "amount" => (int)$currentDishAmount, "image" => $currentDish['image']);
            }
            return $order;

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
    }
}