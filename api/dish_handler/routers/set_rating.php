<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include_once "api/dish_handler/helpers/update_ratings.php";

require "libs/vendor/autoload.php";

function setDishRating($dishID, $requestData)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    $rating = $requestData->parameters['ratingScore'][0];
    if (!isset($rating)) {
        if (sizeof($requestData->parameters)) {
            setHTTPStatus("404", "Request /api/basket/dish/{dishId} accepts only 'ratingScore' as a parameter");
            return;
        }
        $rating = 0;
    }

    if (isset($rating) && is_numeric($rating) && (float)$rating == (int)$rating) {
        if ($rating < 0 || $rating > 10) {
            setHTTPStatus("400", "Rating value should be in range from 0 to 10");
        } else {
            global $Link;
            global $Key;

            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
            if (isset($dish)) {
                include_once "api/dish_handler/helpers/was_ordered_dish.php";
                if (wasOrderedDish($dishID, $userID)) {
                    $currentRating = $Link->query("SELECT * FROM rating WHERE user_id = '$userID' AND dish_id = '$dishID'")->fetch_assoc();
                    if ($currentRating) {
                        $updateResult = $Link->query("UPDATE rating SET rating = '$rating' WHERE user_id = '$userID' AND dish_id = '$dishID'");
                        if (!$updateResult) {
                            setHTTPStatus("500", "Database error: $Link->error");
                        }
                        else {
                            updateRating($dishID);
                        }
                    } else {
                        $insertResult = $Link->query("INSERT INTO rating(user_id, dish_id, rating) VALUES ('$userID', '$dishID', '$rating')");
                        if (!$insertResult) {
                            setHTTPStatus("500", "Database error: $Link->error");
                        }
                        else {
                            updateRating($dishID);
                        }
                    }
                } else {
                    setHTTPStatus("400", "User can't set rating on dish that wasn't ordered");
                }
            } else {
                setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
            }
        }
    } else {
        setHTTPStatus("400", "Rating should be integer value in range from 0 to 10");
    }
}