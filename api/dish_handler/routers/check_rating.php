<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function isAbleToSetRating($dishID)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if(!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;
    $searchResult = $Link->query("SELECT rating FROM rating WHERE user_id = '$userID' AND dish_id = '$dishID'")->fetch_assoc();
    if (!isset($searchResult)) {
        $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
        if (!isset($dish)) {
            setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
        } else {
            include_once "api/dish_handler/helpers/was_ordered_dish.php";
            if (!wasOrderedDish($dishID, $userID)) {
                echo "false";
            } else {
                echo "true";
            }
        }
    } else {
        echo "false";
    }
}