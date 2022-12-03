<?php

function getDishById($dishID)
{

    global $Link;

    $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$dishID'")->fetch_assoc();
    if ($dish) {
        include_once "api/dish/helpers/calculate_rating.php";
        calculateRating($dishID);
        echo json_encode($dish);
    } else {
        setHTTPStatus("404", "There is no dish with such ID: '$dishID'");
    }
}