<?php

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
                    logout($requestData);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint'");
                    break;
            }
            break;

        case "GET":
            switch ($endpoint) {
                case "/api/account/profile":
                    include_once 'api/account_handler/routers/get_profile.php';
                    getProfile($requestData);
                    break;
                default:
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint'");
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
                    setHTTPStatus("404", "There is no such endpoint as: '$endpoint'");
                    break;
            }
            break;

        default:
            setHTTPStatus("404", "There is no such endpoint as: '$endpoint'");
            break;
    }

}