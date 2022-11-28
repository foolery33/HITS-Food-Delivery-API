<?php

/**
 * @throws Exception
 */
function route($method, $urlList, $requestData) {

    global $Link;

    switch ($urlList[2]) {

        case "register":
            register($method, $requestData);
            break;
        case "login":
            login($method, $requestData);
            break;
        case "logout":
            logout($method, $urlList, $requestData);
            break;
        case "profile":
            profile($method, $urlList, $requestData);
            break;
        default:
            setHTTPStatus("404", "There is no such endpoint as api/account/" . $urlList[2]);
    }

}