<?php

function wasOrderedDish($dishID, $userID): bool
{
    global $Link;

    $order = $Link->query("SELECT order_id FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$dishID'")->fetch_all();

    if (isset($order)) {
        foreach ($order as $value) {
            $currentOrderID = $value[0];
            $currentOrderStatus = $Link->query("SELECT status FROM orders WHERE user_id = '$userID' AND order_id = '$currentOrderID'")->fetch_assoc();
            if($currentOrderStatus['status'] == "Delivered") {
                return true;
            }
        }
        return false;
    }

    return false;
}