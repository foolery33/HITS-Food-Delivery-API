<?php

/**
 * @throws Exception
 */
function login($requestData)
{
    global $Link;
    $email = $requestData->body->email;
    $password = hash("sha1", $requestData->body->password);

    $user = $Link->query("SELECT user_id, fullName, birthDate, gender, address, email, phoneNumber FROM user WHERE email = '$email' AND password = '$password'")->fetch_assoc();

    if (!is_null($user)) {
        $token = generateUserToken($user);
        echo json_encode(['token' => $token]);
    } else {
        setHTTPStatus("404", "There is no such user with supplied data");
    }

}