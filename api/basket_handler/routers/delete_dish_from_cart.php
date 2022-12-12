<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function deleteDishFromCart($dishID, $requestData)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;

    $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
    $userID = $decodedToken['data']->id;
    $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'");
    if ($dish) {
        $increase = $requestData->parameters['increase'][0] ?? false;
        switch ($increase) {
            case "true":
                $increase = true;
                break;
            case "false":
            case null:
                $increase = false;
                break;
            default:
                setHTTPStatus("404", "Increase parameter should be either 'true' or 'false'");
                return;
        }

        $dishAmountInCart = (int)$Link->query("SELECT amount FROM dish_basket WHERE dish_id = '$dishID' AND user_id = '$userID'")->fetch_assoc()['amount'];
        // Смотрим флаг increase:
        if ($increase) {
            if ($dishAmountInCart == 1) {
                $deleteResult = $Link->query("DELETE FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$dishID' AND order_id IS NULL");
                if (!$deleteResult) {
                    setHTTPStatus("500", "Database error: $Link->error");
                }
            } else {
                $newAmount = $dishAmountInCart - 1;
                $updateResult = $Link->query("UPDATE dish_basket SET amount = '$newAmount' WHERE user_id = '$userID' AND dish_id = '$dishID' AND order_id IS NULL");
                if (!$updateResult) {
                    setHTTPStatus("500", "Database error: $Link->error");
                }
            }
        } else {
            $deleteResult = $Link->query("DELETE FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$dishID' AND order_id IS NULL");
            if (!$deleteResult) {
                setHTTPStatus("500", "Database error: $Link->error");
            }
        }
    }
}