<?php

function route($method, $urlList, $requestData)
{

    switch ($method) {
        case "GET":
            if (sizeof($urlList) == 2) {
                include_once "api/dish/routers/get_dishes.php";
                getDishes($requestData);
            } elseif (sizeof($urlList) == 3) {
                include_once "api/dish/routers/get_dish_by_id.php";
                getDishById($urlList[2]);
            } elseif (sizeof($urlList) == 5) {
                include_once "api/dish/routers/check_rating.php";
                isAbleToSetRating($urlList[2]);
            } else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("400", "You cannot send GET request to such endpoint: '/$endpoint'");
            }
            break;
        case "POST":
            if(sizeof($urlList) == 4) {
                include_once "api/dish/routers/set_rating.php";
                setDishRating($urlList[2], $requestData);
            }
            else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send POST request to such endpoint: '/$endpoint'");
            }
            break;
        default:
            setHTTPStatus("400", "You can only send GET or POST requests to /api/dish/*");
            break;
    }

}