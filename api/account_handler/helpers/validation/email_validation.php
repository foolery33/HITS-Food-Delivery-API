<?php

function isValidEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    else {
        return "Your e-mail doesn't match to standard e-mail validation";
    }
}