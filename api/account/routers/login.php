<?php

/**
 * @throws Exception
 */
function route($method, $urlList, $requestData)
{
    if ($method == "POST") {
        global $Link;
        switch ($urlList[2]) {
            case "login":
                $email = $requestData->body->email;
                $password = hash("sha1", $requestData->body->password);

                $userID = $Link->query("SELECT id FROM user WHERE email='$email' AND password='$password'")->fetch_assoc()['id'];
                if (!is_null($userID)) {
                    include_once "api/account/helpers/token_generator.php";
                    $user = $Link->query("SELECT id, fullName, birthDate, gender, address, email, phoneNumber FROM user WHERE id = '$userID'")->fetch_assoc();
                    $token = generateUserToken($user);
                    echo json_encode(['token' => $token]);
/*                    $tokenInsertResult = $Link->query("INSERT INTO token(token, user) VALUES('$token', '$userID')");
                    if(!$tokenInsertResult) {
                        echo json_encode($Link->error);
                    }
                    else {
                        echo json_encode(['token' => $token]);
                    }*/
                }
                else {
                    setHTTPStatus("400", "There is no such user with supplied data");
                }
                break;
            case "logout":
                // TODO: later
                break;
            default:
                setHTTPStatus("404", "There is no such path as 'auth/$urlList[2]'");
                break;
        }
    }
    else {
        setHTTPStatus("400", "You can only use POST to 'auth/*'");
    }
}