<?php

function insertOrderID($orderID, $userID, $dishesInCart)
{
    global $Link;

    foreach ($dishesInCart as $value) {
        $insertResult = $Link->query("UPDATE dish_basket SET order_id = '$orderID' WHERE dish_id = '$value[0]' AND user_id = '$userID' AND order_id IS NULL");
        if (!$insertResult) {
            setHTTPStatus("500", "Database error: $Link->error");
        }
    }
}