<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function getUserCart()
{
    $token = substr(getallheaders()['Authorization'], 7);

    if ($token) {
        global $Key;
        try {
            global $Link;
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