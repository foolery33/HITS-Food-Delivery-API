<?php

function route($method, $urlList, $requestData)
{
    switch ($method) {
        case "GET":
            if (sizeof($urlList) == 2) {
                include_once "api/basket_handler/routers/get_user_cart.php";
                getUserCart();
            } else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send GET request to such endpoint: '/$endpoint'");
            }
            break;
        case "POST":
            if (sizeof($urlList) == 4) {
                include_once "api/basket_handler/routers/add_dish_to_cart.php";
                $dishID = $urlList[3];
                addDishToCart($dishID);
            } else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send POST request to such endpoint: '/$endpoint'");
            }
            break;
        case "DELETE":
            if (sizeof($urlList) == 4) {
                include_once "api/basket_handler/routers/delete_dish_from_cart.php";
                $dishID = $urlList[3];
                deleteDishFromCart($dishID, $requestData);
            } else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send DELETE request to such endpoint: '/$endpoint'");
            }
            break;
        default:
            $endpoint = implode('/', $urlList);
            setHTTPStatus("404", "You cannot send $method request to such endpoint: '/$endpoint");
    }
}