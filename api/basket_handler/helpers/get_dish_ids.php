<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function showCartByDishIDs($dishesInCart)
{
    global $Link;

    $token = substr(getallheaders()['Authorization'], 7);
    if ($token) {
        global $Key;
        try {
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $cart = [];
            foreach ($dishesInCart as $value) {
                $currentDishAmount = $Link->query("SELECT amount FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$value[0]' AND order_id IS NULL")->fetch_assoc()['amount'];
                $currentDish = $Link->query("SELECT name, price, image, dish_id FROM dish WHERE dish_id = '$value[0]'")->fetch_assoc();
                $cart[sizeof($cart)] = array("name" => $currentDish['name'], "price" => (float)$currentDish['price'], "totalPrice" => (float)$currentDish['price'] * (int)$currentDishAmount, "amount" => (int)$currentDishAmount, "image" => $currentDish['image'], "id" => $value[0]);
            }
            echo json_encode($cart);

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