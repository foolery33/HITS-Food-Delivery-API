<?php

function getOrderPrice($dishesInCart): float
{
    global $Link;
    $sum = 0.0;

    foreach ($dishesInCart as $value) {
        $dishID = $value[0];
        $dishAmount = $value[1];
        $dish = $Link->query("SELECT price FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
        $sum += (float)$dish['price'] * $dishAmount;
    }
    return $sum;
}