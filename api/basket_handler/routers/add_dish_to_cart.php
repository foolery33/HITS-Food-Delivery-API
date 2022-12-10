<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require "libs/vendor/autoload.php";

function addDishToCart($dishID)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if ($token) {
        global $Key;
        try {
            global $Link;
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'");
            if($dish) {
                // wasNotOrdered = NULL, если такое блюдо в первый раз добавляется в текущую корзину. Иначе это поле равно количеству таких блюд в корзине
                $wasNotOrdered = $Link->query("SELECT amount FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$dishID' AND order_id IS NULL")->fetch_assoc();
                if(isset($wasNotOrdered)) {
                    $newAmount = (int)$wasNotOrdered['amount'] + 1;
                    $updateResult = $Link->query("UPDATE dish_basket SET amount = '$newAmount' WHERE user_id = '$userID' AND dish_id = '$dishID' AND order_id IS NULL");
                    if(!$updateResult) {
                        setHTTPStatus("500", "Database error: $Link->error");
                    }
                }
                else {
                    $insertResult = $Link->query("INSERT INTO dish_basket(user_id, order_id, dish_id, amount) VALUES('$userID', NULL, '$dishID', 1)");
                    if(!$insertResult) {
                        setHTTPStatus("500", "Database error: $Link->error");
                    }
                }
            }
            else {
                setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
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