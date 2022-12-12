<?php

function getBodyData($method): stdClass
{

    $data = new stdClass();

    if ($method != "GET") {
        $data->body = json_decode(file_get_contents('php://input'));
        if($data->body == null) {
            $jsonError = json_last_error_msg();
            setHTTPStatus("400", "Not valid JSON string: $jsonError");
            die;
        }
    }
    $data->parameters = [];
    $query = explode('&', $_SERVER['QUERY_STRING']);

    foreach ($query as $parameter) {
        if ($parameter[0] != "q") {
            list($name, $value) = explode('=', $parameter, 2);
            $data->parameters[urldecode($name)][] = urldecode($value);
        }
    }
    return $data;
}