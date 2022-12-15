<?php

function registerValidation($email, $phoneNumber, $password, $birthDate)
{

    include_once "api/account_handler/helpers/validation/email_validation.php";
    include_once "api/account_handler/helpers/validation/phone_number_validation.php";
    include_once "api/account_handler/helpers/validation/password_validation.php";

    $message = [];

    $emailMessage = isValidEmail($email);
    $phoneNumberMessage = isValidPhoneNumber($phoneNumber);
    $passwordMessage = isValidPassword($password);

    if ($emailMessage != "true") {
        $message['Email'] = [$emailMessage];
    }
    if ($phoneNumberMessage != "true") {
        $message['PhoneNumber'] = [$phoneNumberMessage];
    }
    if ($passwordMessage != "true") {
        $message['Password'] = explode('.', $passwordMessage);
    }
    if (isset($birthDate)) {
        include_once "helpers/valid_time_format.php";
        include_once "helpers/time_to_int.php";
        if (isValidTimeFormat($birthDate)) {
            if(timeToInt($birthDate) > time()) {
                $message['BirthDate'] = ['Birth date can\'t be later that current time moment'];
            }
        } else {
            setHTTPStatus("400", "Provided 'deliveryTime' data doesn't match to required format");
        }
    }

    if (sizeof($message) != 0) {
        return $message;
    } else {
        return true;
    }

}