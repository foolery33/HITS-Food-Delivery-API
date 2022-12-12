<?php

function route($method, $urlList, $requestData)
{

    $endpoint = '/' . implode('/', $urlList);

    switch ($method) {

        case "GET":
            switch ($endpoint) {
                case "/api/dish":
                    include_once "api/dish_handler/routers/get_dishes.php";
                    getDishes($requestData);
                    break;
                case "/api/dish/$urlList[2]":
                    include_once "api/dish_handler/routers/get_dish_by_id.php";
                    getDishById($urlList[2]);
                    break;
                case "/api/dish/$urlList[2]/rating/check":
                    include_once "api/dish_handler/routers/check_rating.php";
                    isAbleToSetRating($urlList[2]);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "POST":
            switch ($endpoint) {
                case "/api/dish/$urlList[2]/rating":
                    include_once "api/dish_handler/routers/set_rating.php";
                    setDishRating($urlList[2], $requestData);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        default:
            setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
            break;
    }
}