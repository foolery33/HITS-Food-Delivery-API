<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function getUserCart()
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;
    $dishesInCart = $Link->query("SELECT dish_id FROM dish_basket WHERE user_id = '$userID' AND order_id IS NULL")->fetch_all();

    if(sizeof($dishesInCart)) {
        include_once "api/basket_handler/helpers/get_dish_ids.php";
        showCartByDishIDs($dishesInCart);
    }
    else {
        echo json_encode(array());
    }
}