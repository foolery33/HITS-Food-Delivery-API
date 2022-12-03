<?php

function calculateRating($dishID)
{
    global $Link;
    $sumOfRatings = 0;
    $ratingsCounter = 0;
    $ratings = $Link->query("SELECT rating FROM rating WHERE dish_id = '$dishID' AND rating IS NOT NULL")->fetch_all();

    foreach ($ratings as $value) {
        $sumOfRatings += (int)$value[0];
        $ratingsCounter += 1;
    }
    if ($ratingsCounter == 0) {
        return null;
    } else {
        return (float)$sumOfRatings / $ratingsCounter;
    }
}