<?php

function getCurrentDateAndTime(): string
{
    date_default_timezone_set('Europe/Moscow');
    $currentTime = new DateTimeImmutable();
    $currentTime = $currentTime->format('Y-m-d H:i:s.v');
    $currentTime[10] = "T";
    $currentTime .= "Z";
    return $currentTime;
}