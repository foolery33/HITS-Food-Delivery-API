<?php

/**
 * @throws Exception
 */
function route($method, $urlList, $requestData)
{
    if ($method == "POST") {
        global $Link;
        switch ($urlList[1]) {
            case "login":
                $email = $requestData->body->email;
                $password = hash("sha1", $requestData->body->password);

                $user = $Link->query("SELECT id FROM user WHERE email='$email' AND password='$password'")->fetch_assoc();
                if (!is_null($user)) {
                    $token = bin2hex(random_bytes(128));
                    $userID = $user['id'];
                    $tokenInsertResult = $Link->query("INSERT INTO token(value, user) VALUES('$token', '$userID')");
                    if(!$tokenInsertResult) {
                        echo json_encode($Link->error);
                    }
                    else {
                        echo json_encode(['token' => $token]);
                    }
                }
                else {
                    setHTTPStatus("400", "There is no such user with supplied data");
                }
                break;
            case "logout":
                // TODO: later
                break;
            default:
                setHTTPStatus("404", "There is no such path as 'auth/$urlList[1]'");
                break;
        }
    }
    else {
        setHTTPStatus("400", "You can only use POST to 'auth/*'");
    }
}