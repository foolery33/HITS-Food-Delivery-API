<?php

function route($method, $urlList, $requestData)
{
    switch ($method) {
        case "POST":
            if (sizeof($urlList) == 2) {
                include_once "api/orders/routers/create_order.php";
                createOrder($requestData);
            }
            break;
        case "GET":
            if (sizeof($urlList) == 2) {
                include_once "api/orders/routers/get_order_list.php";
                getOrderList();
            }
            break;
        default:
            break;
    }
}