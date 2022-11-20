<?php

include_once 'helpers/headers.php';
include_once 'helpers/id_maker.php';

global $Link;

function getData($method)
{

    $data = new stdClass();

    if ($method != "GET") {
        $data->body = json_decode(file_get_contents('php://input'));
    }

    $data->parameters = [];
    $dataGet = $_GET;
    foreach ($dataGet as $key => $value) {
        if ($key != "q") {
            $data->parameters[$key] = $value;
        }
    }
    return $data;
}

function getMethod()
{
    return $_SERVER['REQUEST_METHOD'];
}

header('Content-type: application/json');
$Link = mysqli_connect("127.0.0.1", "php_project", "nikitausov", "php_project");

if (!$Link) {
    setHTTPStatus("500", "DB Connection error: " .mysqli_connect_error());
}
$url = isset($_GET['q']) ? $_GET['q'] : '';
$url = rtrim($url, '/');
$urlList = explode('/', $url);

$router = $urlList[0];
$requestData = getData(getMethod());

if (file_exists(realpath(dirname(__FILE__)) . '/' . $urlList[0] . '/' . $urlList[1] . '/routers/' . $urlList[2] . '.php')) {
    include_once $urlList[0] . '/' . $urlList[1] . '/routers/' . $urlList[2] . '.php';
    route(getMethod(), $urlList, $requestData);
} else {
    setHTTPStatus("404", "There is no such endpoint as '/routers/" . $router);
}