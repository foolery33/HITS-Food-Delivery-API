<?php

function getDishById($id)
{

    global $Link;

    $dish = $Link->query("SELECT * FROM dish WHERE dish_id = '$id'")->fetch_assoc();
    if ($dish) {
        include_once "api/dish/helpers/calculate_rating.php";
        calculateRating($id);
        echo json_encode($dish);
    } else {
        setHTTPStatus("400", "Invalid dish ID");
    }
}