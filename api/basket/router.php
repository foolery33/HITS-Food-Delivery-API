<?php

function route($method, $urlList, $requestData)
{
    switch ($method) {
        case "GET":
            break;
        case "POST":
            if(sizeof($urlList) == 4) {
                include_once "api/basket/routers/add_dish_to_cart.php";
                $dishID = $urlList[3];
                addDishToCart($dishID);
            }
            else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send POST request to such endpoint: '/$endpoint'");
            }
            break;
        case "DELETE":
            break;
    }
}