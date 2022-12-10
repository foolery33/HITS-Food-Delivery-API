<?php

function route($method, $urlList, $requestData)
{
    switch ($method) {
        case "POST":
            if (sizeof($urlList) == 2) {
                include_once "api/order_handler/routers/create_order.php";
                createOrder($requestData);
            }
            elseif (sizeof($urlList) == 4) {
                include_once "api/order_handler/routers/confirm_order_delivery.php";
                confirmOrderDelivery($urlList[2]);
            }
            else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send POST request to '/$endpoint'");
            }
            break;
        case "GET":
            if (sizeof($urlList) == 2) {
                include_once "api/order_handler/routers/get_order_list.php";
                getOrderList();
            }
            elseif (sizeof($urlList) == 3) {
                include_once "api/order_handler/routers/get_order_by_id.php";
                getOrderById($urlList[2]);
            }
            else {
                $endpoint = implode('/', $urlList);
                setHTTPStatus("404", "You cannot send GET request to '/$endpoint'");
            }
            break;
        default:
            $endpoint = implode('/', $urlList);
            setHTTPStatus("404", "You cannot send $method request to '/$endpoint'");
            break;
    }
}