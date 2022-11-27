<?php

function isValidPassword($password)
{

    $errors = [];
    if (preg_match('/.*[0-9].*/', $password)) {
        if (preg_match('/.*[a-zA-Z]/', $password)) {
            if (!preg_match('/.* +.*/', $password)) {
                if (preg_match('/.{6,}/', $password)) {
                    return true;
                }
            }
        }
    }

    if (!preg_match('/.*[0-9].*/', $password)) {
        $errors[] = "Your password should contain at least one digit";
    }
    if (!preg_match('/.*[a-zA-Z]/', $password)) {
        $errors[] = "Your password should contain at least one letter";
    }
    if (preg_match('/.* +.*/', $password)) {
        $errors[] = "Your password should not contain any whitespaces";
    }
    if (!preg_match('/.{6,}/', $password)) {
        $errors[] = "Your password should be at least 6 characters long";
    }

    $message = "";
    for ($i = 0; $i < count($errors); $i++) {
        $message .= $errors[$i] . ". ";
    }

    if(strlen($message) > 0) {
        $message = substr($message, 0, strlen($message) - 1);
    }
    return $message;
}