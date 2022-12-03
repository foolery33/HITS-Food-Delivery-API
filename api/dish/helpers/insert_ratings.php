<?php

function insert_ratings($query) {

    include_once "api/dish/helpers/calculate_rating.php";

    global $Link;
    $dishes = $Link->query($query)->fetch_all();
    foreach ($dishes as $value) {
        $dishID = $value[0];
        $rating = calculateRating($dishID);

        if(!isset($rating)) {
            $rating = "NULL";
        }
        else {
            $rating = '\'' . $rating . '\'';
        }
        $Link->query("UPDATE dish SET rating = $rating WHERE dish_id = '$dishID'");
    }

}