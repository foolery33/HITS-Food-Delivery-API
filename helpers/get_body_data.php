<?php

function getBodyData($method): stdClass
{

    $data = new stdClass();

    if ($method != "GET") {
        $data->body = json_decode(file_get_contents('php://input'));
    }
    $data->parameters = [];
    $dataGet = $_GET;
    $query = explode('&', $_SERVER['QUERY_STRING']);

    foreach ($query as $parameter) {
        if ($parameter[0] != "q") {
            list($name, $value) = explode('=', $parameter, 2);
            $data->parameters[urldecode($name)][] = urldecode($value);
            /*if($data->parameters[$key] != "") {
                $data->parameters[$key] .= ', ' . $key;
            }
            else {
                $data->parameters[$key] = $value;
            }*/
        }
    }
    return $data;
}