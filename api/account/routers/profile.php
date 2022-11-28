<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

function profile($method, $requestData)
{
    if ($method == "GET" || $method == "PUT") {
        global $Key;
        $token = substr(getallheaders()['Authorization'], 7);
        include_once 'helpers/check_token_expiration.php';
        $expirationStatus = isExpiredToken($token);
        switch ($expirationStatus) {
            case false:
                switch ($method) {
                    case "GET":
                        $decoded = (array)JWT::decode($token, new Key($Key, 'HS256'));
                        echo json_encode($decoded['data']);
                        break;
                    case "PUT":
                        global $Link;
                        $errors = [];
                        $user = ((array)JWT::decode($token, new Key($Key, 'HS256')))['data'];
                        $userID = $user->id;
                        $newFullName = $requestData->body->fullName;
                        if (!strlen($newFullName)) {
                            $errors['FullName'] = ["The FullName field is required"];
                        }
                        $newBirthDate = empty($requestData->body->birthDate) ? $user->birthDate : $requestData->body->birthDate;
                        $newGender = empty($requestData->body->gender) ? $user->gender : $requestData->body->gender;
                        $newAddress = empty($requestData->body->address) ? $user->address : $requestData->body->address;
                        $newPhoneNumber = empty($requestData->body->phoneNumber) ? $user->phoneNumber : $requestData->body->phoneNumber;
                        if (sizeof($errors) == 0) {
                            $updateResult = $Link->query("UPDATE user SET fullName = '$newFullName', birthDate = '$newBirthDate', gender = '$newGender', address = '$newAddress', phoneNumber = '$newPhoneNumber' WHERE id = '$userID'");
                            if (!$updateResult) {
                                setHTTPStatus("400", "DB error: $Link->error");
                            } else {
                                setHTTPStatus("200", "Success");
                            }
                            break;
                        } else {
                            setHTTPStatus("400", $errors);
                        }
                }
                break;
            case "expired":
                setHTTPStatus("401", "Your token is expired");
                break;
            case "invalid":
                setHTTPStatus("401", "Your token is not valid");
                break;
        }
    } else {
        setHTTPStatus("400", "You can only send GET or PUT requests to 'profile'");
    }
}