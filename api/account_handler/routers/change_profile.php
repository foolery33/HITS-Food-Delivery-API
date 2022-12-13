<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "libs/vendor/autoload.php";

/*
 * Функция, изменяющая в базе данных информацию о пользователе
 */

function changeProfile($requestData)
{
    $token = substr(getallheaders()['Authorization'], 7);
    if (!isGoodToken($token)) {
        return;
    }

    global $Link;
    global $Key;
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

    include_once "api/account_handler/helpers/validation/phone_number_validation.php";
    $phoneMessage = isValidPhoneNumber($newPhoneNumber);
    if ($phoneMessage != "true") {
        $message['PhoneNumber'] = [$phoneMessage];
        echo json_encode($message);
        return;
    }

    if (sizeof($errors) == 0) {
        $updateResult = $Link->query("UPDATE user SET fullName = '$newFullName', birthDate = '$newBirthDate', gender = '$newGender', address = '$newAddress', phoneNumber = '$newPhoneNumber' WHERE user_id = '$userID'");
        if (!$updateResult) {
            setHTTPStatus("400", "DB error: $Link->error");
        } else {
            setHTTPStatus("200", "Success");
        }
    } else {
        setHTTPStatus("400", $errors);
    }
}