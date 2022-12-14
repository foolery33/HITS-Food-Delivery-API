<?php

function areAccurateDishesParameters($parameters, $correctParameters): bool
{
    $parametersCounter = 0;

    if (isset($parameters['categories'])) {
        $parametersCounter++;
    }
    if (isset($parameters['vegetarian'])) {
        $parametersCounter++;
    }
    if (isset($parameters['page'])) {
        $parametersCounter++;
    }
    if (isset($parameters['sorting'])) {
        $parametersCounter++;
    }

    if ($parametersCounter != sizeof($parameters)) {
        $errors = [];
        foreach ($parameters as $key => $value) {
            if (!in_array($key, $correctParameters)) {
                $errors[] = "Parameter '$key' is not used in /api/dish request";
            }
        }
        setHTTPStatus("404", $errors);
        return false;
    }
    return true;
}