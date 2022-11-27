<?php

function registerValidation($email, $phoneNumber, $password) {

    include_once "api/account/helpers/validation/email_validation.php";
    include_once "api/account/helpers/validation/phone_number_validation.php";
    include_once "api/account/helpers/validation/password_validation.php";

    $message = "";

    $emailMessage = isValidEmail($email);
    $phoneNumberMessage = isValidPhoneNumber($phoneNumber);
    $passwordMessage = isValidPassword($password);

    if($emailMessage != "true") {
        $message .= "E-mail errors: " . $emailMessage . ". ";
    }
    if($phoneNumberMessage != "true") {
        $message .= "Phone number errors: " . $phoneNumberMessage . ". ";
    }
    if($passwordMessage != "true") {
        $message .= "Password errors: " . $passwordMessage . " ";
    }

    if(strlen($message) > 0) {
        return substr($message, 0, strlen($message) - 1);
    }
    else {
        return true;
    }

}