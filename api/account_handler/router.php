<?php

include_once "helpers/check_token_expiration.php";

/**
 * @throws Exception
 */
function route($method, $urlList, $requestData)
{

    $endpoint = '/' . implode('/', $urlList);

    switch ($method) {

        case "POST":
            switch ($endpoint) {
                case "/api/account/register":
                    include_once 'api/account_handler/routers/register.php';
                    register($requestData);
                    break;
                case "/api/account/login":
                    include_once 'api/account_handler/routers/login.php';
                    login($requestData);
                    break;
                case "/api/account/logout":
                    include_once 'api/account_handler/routers/logout.php';
                    logout();
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "GET":
            switch ($endpoint) {
                case "/api/account/profile":
                    include_once 'api/account_handler/routers/get_profile.php';
                    getProfile();
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint' with $method type of request");
                    break;
            }
            break;

        case "PUT":
            switch ($endpoint) {
                case "/api/account/profile":
                    include_once 'api/account_handler/routers/change_profile.php';
                    changeProfile($requestData);
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