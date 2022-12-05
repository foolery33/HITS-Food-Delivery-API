<?php

function isValidTimeFormat($currentTime): bool
{
    try {
        $currentTime = substr($currentTime, 0, strlen($currentTime) - 1);
        $currentTime[10] = " ";

        $currentTimeInt = strtotime($currentTime);

        if ($currentTimeInt == 1 || !$currentTimeInt) {
            return false;
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}