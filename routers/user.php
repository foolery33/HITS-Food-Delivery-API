<?php

function uid() {
    mt_srand((double)microtime()*1000000);
    $token = mt_rand(1, mt_getrandmax());

    $uid = uniqid(md5($token), true);
    if($uid != false && $uid != '' && $uid != NULL) {
        $out = sha1($uid);
        return $out;
    } else {
        return false;
    }
}

function route($method, $urlList, $requestData)
{
    global $Link;
    switch ($method) {
        case 'GET':
            $token = substr(getallheaders()['Authorization'], 7);
            $userFromToken = $Link->query("SELECT user FROM token WHERE value='$token'")->fetch_assoc();
            if(!is_null($userFromToken)) {
                $userID = $userFromToken['user'];
                $user = $Link->query("SELECT * FROM user WHERE id='$userID'")->fetch_assoc();
                echo json_encode($user);
            }
            else {
                echo "400: input data incorrect";
            }
            break;
        case 'POST':
            $email = $requestData->body->email;
            $user = $Link->query("SELECT id FROM user WHERE email='$email'")->fetch_assoc();

            if (is_null($user)) {

                $password = hash("sha1", $requestData->body->password);
                $fullName = $requestData->body->fullName;
                $address = $requestData->body->address;
                $birthDate = $requestData->body->birthDate;
                $gender = $requestData->body->gender;
                $phoneNumber = $requestData->body->phoneNumber;
                $id = uid();

                $userInsertResult = $Link->query("INSERT INTO user(id, fullName, birthDate, gender, address, email, phoneNumber, password) VALUES ('$id', '$fullName', '$birthDate', '$gender', '$address', '$email', '$phoneNumber', '$password')");

                if(!$userInsertResult) {
                    echo "Too bad";
                }
                else {
                    echo "Success";
                }

                echo json_encode($requestData);
            } else {
                echo "EXIST";
            }

            break;
    }
}