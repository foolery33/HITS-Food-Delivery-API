<?php

include_once 'helpers/headers.php';
include_once 'api/account_handler/helpers/generators/id_generator.php';
include_once 'api/account_handler/helpers/generators/token_generator.php';
include_once 'helpers/get_body_data.php';
include_once 'helpers/get_request_method.php';
include_once 'api/account_handler/helpers/validation/general_validation.php';
include_once 'helpers/is_good_token.php';

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

if (file_exists(realpath(dirname(__FILE__)) . '/' . $urlList[0] . '/' . $urlList[1] . '_handler/router.php')) {
    include_once $urlList[0] . '/' . $urlList[1] . '_handler/router.php';
    route(getRequestMethod(), $urlList, $requestData);
} else {
    setHTTPStatus("404", "Incorrect URL request");
}