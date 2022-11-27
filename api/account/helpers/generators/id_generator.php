<?php

function userId()
{
    mt_srand((double)microtime() * 1000000);
    $token = mt_rand(1, mt_getrandmax());

    $uid = uniqid(md5($token), true);
    if ($uid != false && $uid != '' && $uid != NULL) {
        $out = sha1($uid);
        return $out;
    } else {
        return false;
    }
}
