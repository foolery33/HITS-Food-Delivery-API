<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function confirmOrderDelivery($orderID)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;

    // Проверка на то, что пользователь не пытается подтвердить доставку чужого заказа
    $order = $Link->query("SELECT * FROM orders WHERE order_id = '$orderID' AND user_id != '$userID'")->fetch_assoc();
    if (isset($order)) {
        setHTTPStatus("403", "You try to confirm order which was made by another user");
        return;
    }

    // Пользователь подтверждает свой заказ
    $order = $Link->query("SELECT * FROM orders WHERE order_id = '$orderID' AND user_id = '$userID'")->fetch_assoc();
    if (isset($order)) {
        $updateResult = $Link->query("UPDATE orders SET status = 'Delivered' WHERE order_id = '$orderID' AND user_id = '$userID'");
        if (!$updateResult) {
            setHTTPStatus("500", "Database error: $Link->error");
        }
    } else {
        setHTTPStatus("404", "There is no order with such id: '$orderID' made by user with id: '$userID'");
    }
}