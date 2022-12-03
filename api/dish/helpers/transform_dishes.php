<?php

function transformDishes($dishes): array
{
    $transformedDishes = [];
    for ($i = 0; $i < sizeof($dishes); $i++) {
        $transformedDishes[$i] = array(
            "name" => $dishes[$i][1], "description" => $dishes[$i][2],
            "price" => (int)$dishes[$i][3], "image", $dishes[$i][4],
            "vegetarian" => (bool)$dishes[$i][5], "rating" => isset($dishes[$i][6]) ? (float)$dishes[$i][6] : null,
            "category" => $dishes[$i][7], "id" => $dishes[$i][0]);
    }
    return $transformedDishes;
}