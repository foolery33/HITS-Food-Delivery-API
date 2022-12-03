<?php

/**
 * @throws Exception
 */
function route($method, $urlList, $requestData) {

    if(sizeof($urlList) != 3) {
        setHTTPStatus("400", "Incorrect URL request");
    }
    else {
        switch ($urlList[2]) {

            case "register":
                register($method, $requestData);
                break;
            case "login":
                login($method, $requestData);
                break;
            case "logout":
                logout($method);
                break;
            case "profile":
                profile($method, $requestData);
                break;
            default:
                setHTTPStatus("404", "There is no such endpoint as api/account/" . $urlList[2]);
        }
    }

}