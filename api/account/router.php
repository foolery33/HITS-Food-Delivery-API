<?php

function route($method, $urlList, $requestData) {

    global $Link;

    switch ($urlList[2]) {

        case "register":
            include_once "api/account/routers/register.php";
            include_once "api/account/helpers/validation/general_validation.php";
            register($method, $requestData);
            break;

        case "login":
            break;
        default:
            setHTTPStatus("404", "There is no such endpoint as api/account/" . $urlList[2]);
    }

}