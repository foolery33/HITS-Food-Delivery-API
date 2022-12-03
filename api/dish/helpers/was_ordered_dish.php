<?php

function wasOrderedDish($dishID, $userID): bool
{
    global $Link;

    $order = $Link->query("SELECT order_id FROM dish_basket WHERE user_id = '$userID' AND dish_id = '$dishID'")->fetch_assoc();

    return isset($order);

}