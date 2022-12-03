<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function setDishRating($dishID, $requestData)
{

    $rating = $requestData->parameters['ratingScore'][0];

    if (isset($rating) && is_numeric($rating) && (float)$rating == (int)$rating) {
        if($rating < 0 || $rating > 10) {
            setHTTPStatus("400", "Rating value should be in range from 0 to 10");
            return;
        }
        $token = substr(getallheaders()['Authorization'], 7);
        if ($token) {
            global $Link;
            global $Key;
            try {
                $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
                $userID = $decodedToken['data']->id;
                $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'");
                if(isset($dish)) {
                    include_once "api/dish/helpers/was_ordered_dish.php";
                    if(wasOrderedDish($dishID, $userID)) {
                        $insertResult = $Link->query("INSERT INTO rating(user_id, dish_id, rating) VALUES ('$userID', '$dishID', '$rating')");
                        if(!$insertResult) {
                            setHTTPStatus("500", "Database error: " . $Link->error);
                        }
                    }
                    else {
                        setHTTPStatus("400", "User can't set rating on dish that wasn't ordered");
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
    } else {
        setHTTPStatus("400", "Rating value should be integer numeric value");
    }
}