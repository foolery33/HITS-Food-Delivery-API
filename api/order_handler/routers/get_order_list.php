<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function getOrderList()
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;

    $orders = $Link->query("SELECT order_id, deliveryTime, orderTime, status, price FROM orders WHERE user_id = '$userID'")->fetch_all();
    $ordersArray = [];
    if ($orders) {
        for ($i = 0; $i < sizeof($orders); $i++) {
            $ordersArray[$i] = array("id" => $orders[$i][0], "deliveryTime" => $orders[$i][1], "orderTime" => $orders[$i][2], "status" => $orders[$i][3], "price" => (float)$orders[$i][4]);
        }
    }
    echo json_encode($ordersArray);
}