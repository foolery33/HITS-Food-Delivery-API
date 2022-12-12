<?php

function route($method, $urlList, $requestData)
{

    $endpoint = '/' . implode('/', $urlList);

    switch ($method) {

        case "GET":
            switch ($endpoint) {
                case "/api/basket":
                    include_once "api/basket_handler/routers/get_user_cart.php";
                    getUserCart();
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "POST":
            switch ($endpoint) {
                case "/api/basket/dish/$urlList[3]":
                    include_once "api/basket_handler/routers/add_dish_to_cart.php";
                    addDishToCart($urlList[3]);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "DELETE":
            switch ($endpoint) {
                case "/api/basket/dish/$urlList[3]":
                    include_once "api/basket_handler/routers/delete_dish_from_cart.php";
                    deleteDishFromCart($urlList[3], $requestData);
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