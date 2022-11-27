<?php

function isValidPhoneNumber($phoneNumber) {
    if(preg_match('/^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/', $phoneNumber)) {
        return true;
    }
    return "Your phone number should match this mask: +7 (xxx) xxx-xx-xx, where 'x' is a digit";
}