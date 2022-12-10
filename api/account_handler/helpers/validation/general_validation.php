<?php

function registerValidation($email, $phoneNumber, $password) {

    include_once "api/account_handler/helpers/validation/email_validation.php";
    include_once "api/account_handler/helpers/validation/phone_number_validation.php";
    include_once "api/account_handler/helpers/validation/password_validation.php";

    $message = [];

    $emailMessage = isValidEmail($email);
    $phoneNumberMessage = isValidPhoneNumber($phoneNumber);
    $passwordMessage = isValidPassword($password);

    if($emailMessage != "true") {
        $message['Email'] = [$emailMessage];
    }
    if($phoneNumberMessage != "true") {
        $message['PhoneNumber'] = [$phoneNumberMessage];
    }
    if($passwordMessage != "true") {
        $message['Password'] = explode('.', $passwordMessage);
    }

    if(sizeof($message) != 0) {
        return $message;
    }
    else {
        return true;
    }

}