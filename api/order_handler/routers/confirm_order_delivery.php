<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function confirmOrderDelivery($orderID)
{
    global $Link;
    $token = substr(getallheaders()['Authorization'], 7);

    if ($token) {
        global $Key;
        try {
            $decodedToken = (array)JWT::decode($token, new Key($Key, 'HS256'));
            $userID = $decodedToken['data']->id;
            $order = $Link->query("SELECT * FROM orders WHERE order_id = '$orderID' AND user_id = '$userID'")->fetch_assoc();
            if(isset($order)) {
                $updateResult = $Link->query("UPDATE orders SET status = 'Delivered' WHERE order_id = '$orderID' AND user_id = '$userID'");
                if(!$updateResult) {
                    setHTTPStatus("500", "Database error: $Link->error");
                }
            }
            else {
                setHTTPStatus("404", "There is no order with such id: '$orderID' made by user with id: '$userID'");
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