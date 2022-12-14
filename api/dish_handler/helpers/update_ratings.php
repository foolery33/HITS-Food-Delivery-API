<?php

function updateRating($dishID) {

    include_once "api/dish_handler/helpers/calculate_rating.php";

    global $Link;
    $rating = calculateRating($dishID);

    if(!isset($rating)) {
        $rating = "NULL";
    }
    else {
        $rating = '\'' . $rating . '\'';
    }
    $updateResult = $Link->query("UPDATE dish SET rating = $rating WHERE dish_id = '$dishID'");

    if(!$updateResult) {
        setHTTPStatus("500", "Database error: $Link->error");
    }
}