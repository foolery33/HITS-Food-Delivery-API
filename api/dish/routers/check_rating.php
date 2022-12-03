<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function isAbleToSetRating($dishID)
{
    global $Link;
    $token = substr(getallheaders()['Authorization'], 7);
    if ($token) {
        global $Key;
        try {
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $searchResult = $Link->query("SELECT rating FROM rating WHERE user_id = '$userID' AND dish_id = '$dishID'")->fetch_assoc();
            if (!isset($searchResult)) {
                $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
                if (!isset($dish)) {
                    setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
                } else {
                    include_once "api/dish/helpers/was_ordered_dish.php";
                    if (!wasOrderedDish($dishID, $userID)) {
                        echo "false";
                    } else {
                        echo "true";
                    }
                }
            } else {
                echo "false";
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