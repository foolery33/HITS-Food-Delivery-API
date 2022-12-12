<?php

function route($method, $urlList, $requestData)
{

    $endpoint = '/' . implode('/', $urlList);

    switch ($method) {

        case "GET":
            switch ($endpoint) {
                case "/api/order":
                    include_once "api/order_handler/routers/get_order_list.php";
                    getOrderList();
                    break;
                case "/api/order/$urlList[2]":
                    include_once "api/order_handler/routers/get_order_by_id.php";
                    getOrderById($urlList[2]);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "POST":
            switch ($endpoint) {
                case "/api/order":
                    include_once "api/order_handler/routers/create_order.php";
                    createOrder($requestData);
                    break;
                case "/api/order/$urlList[2]/status":
                    include_once "api/order_handler/routers/confirm_order_delivery.php";
                    confirmOrderDelivery($urlList[2]);
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