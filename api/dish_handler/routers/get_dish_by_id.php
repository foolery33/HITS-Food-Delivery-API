<?php

function getDishById($dishID)
{

    global $Link;

    $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
    if ($dish) {
        $dishData = array(
            "id" => $dish['dish_id'],
            "name" => $dish['name'],
            "description" => $dish['description'],
            "price" => (float)$dish['price'],
            "image" => $dish['image'],
            "vegetarian" => (bool)$dish['vegetarian'],
            "rating" => isset($dish['rating']) ? (float)$dish['rating'] : null,
            "category" => $dish['category']
        );
        echo json_encode($dishData);
    } else {
        setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
    }
}