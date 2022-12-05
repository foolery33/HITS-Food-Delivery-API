<?php

include_once 'helpers/headers.php';
include_once 'api/account/helpers/generators/id_generator.php';
include_once 'api/account/helpers/generators/token_generator.php';
include_once 'helpers/get_body_data.php';
include_once 'helpers/get_request_method.php';
include_once 'api/account/routers/register.php';
include_once 'api/account/routers/login.php';
include_once 'api/account/routers/logout.php';
include_once 'api/account/routers/profile.php';
include_once 'api/account/helpers/validation/general_validation.php';
include_once 'helpers/check_token_expiration.php';

global $Link;
global $Key;

header('Content-type: application/json');
$Link = mysqli_connect("127.0.0.1", "php_project", "nikitausov", "php_project");
$Key = "blessRNG";

if (!$Link) {
    setHTTPStatus("500", "DB Connection error: " . mysqli_connect_error());
}
$url = $_GET['q'] ?? '';
$url = rtrim($url, '/');
$urlList = explode('/', $url);

$router = $urlList[0];
$requestData = getBodyData(getRequestMethod());

if (file_exists(realpath(dirname(__FILE__)) . '/' . $urlList[0] . '/' . $urlList[1] . '/router.php') ||
    file_exists(realpath(dirname(__FILE__)) . '/' . $urlList[0] . '/' . $urlList[1] . 's/router.php')) {
    if ($urlList[1] == "order") {
        include_once $urlList[0] . '/' . $urlList[1] . 's/router.php';
    } else {
        include_once $urlList[0] . '/' . $urlList[1] . '/router.php';
    }
    route(getRequestMethod(), $urlList, $requestData);
} else {
    setHTTPStatus("404", "Incorrect URL request");
}