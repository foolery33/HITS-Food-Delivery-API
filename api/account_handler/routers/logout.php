<?php

function logout()
{
    $token = substr(getallheaders()['Authorization'], 7);
    if(!isGoodToken($token)) {
        return;
    }

    global $Link;
    $searchResult = $Link->query("SELECT token FROM token_blacklist WHERE token = '$token'")->fetch_assoc();
    if (!$searchResult) {
        $Link->query("INSERT INTO token_blacklist(token) VALUES ('$token')");
        setHTTPStatus("200", "Logged out");
    } else {
        setHTTPStatus("401", "Your token is expired");
    }

}