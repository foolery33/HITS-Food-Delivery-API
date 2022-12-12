<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function getOrderById($orderID)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;
    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;

    // Проверка на то, что пользователь не пытается посмотреть информацию о чужом заказе
    $order = $Link->query("SELECT order_id, deliveryTime, orderTime, status, price, address FROM orders WHERE order_id = '$orderID' AND user_id != '$userID'")->fetch_assoc();
    if(isset($order)) {
        setHTTPStatus("403", "You try to receive data about order which is made by another user");
        return;
    }

    // Пользователь смотрит информацию о своём заказе
    $order = $Link->query("SELECT order_id, deliveryTime, orderTime, status, price, address FROM orders WHERE order_id = '$orderID' AND user_id = '$userID'")->fetch_assoc();
    if ($order) {

        $dishesInOrder = $Link->query("SELECT dish_id FROM dish_basket WHERE user_id = '$userID' AND order_id = '$orderID'")->fetch_all();
        include_once "api/order_handler/helpers/get_order_dishes_info.php";
        $dishesInOrder = getOrderDishesInfo($dishesInOrder, $orderID);

        $orderData = array(
            "id" => $orderID,
            "deliveryTime" => $order['deliveryTime'],
            "orderTime" => $order['orderTime'],
            "status" => $order['status'],
            "price" => (float)$order['price'],
            "dishes" => $dishesInOrder,
            "address" => $order['address']
        );

        echo json_encode($orderData);
    } else {
        setHTTPStatus("404", "There is no order with such id: '$orderID' made by user with id: '$userID'");
    }
}