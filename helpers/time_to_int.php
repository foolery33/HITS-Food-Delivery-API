<?php

function timeToInt($time)
{
    $time = substr($time, 0, strlen($time) - 1);
    $time[10] = " ";

    return strtotime($time);
}