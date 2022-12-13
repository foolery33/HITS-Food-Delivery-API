<?php

function transformDishes($dishes): array
{
    $transformedDishes = [];
    for ($i = 0; $i < sizeof($dishes); $i++) {
        $transformedDishes[$i] = array(
            "id" => $dishes[$i][0],
            "name" => $dishes[$i][1],
            "description" => $dishes[$i][2],
            "price" => (float)$dishes[$i][3],
            "image" => $dishes[$i][4],
            "vegetarian" => (bool)$dishes[$i][5],
            "rating" => isset($dishes[$i][6]) ? (float)$dishes[$i][6] : null,
            "category" => $dishes[$i][7]
        );
    }
    return $transformedDishes;
}