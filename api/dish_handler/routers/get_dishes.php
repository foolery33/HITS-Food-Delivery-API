<?php

function getDishes($requestData)
{

    global $Link;

    $categories = (isset($requestData->parameters['categories'])) ? $requestData->parameters['categories'] : null;
    $vegetarian = (isset($requestData->parameters['vegetarian'])) ? $requestData->parameters['vegetarian'] : null;
    $sorting = (isset($requestData->parameters['sorting'])) ? $requestData->parameters['sorting'] : null;
    $page = (isset($requestData->parameters['page'])) ? $requestData->parameters['page'] : null;

    $dishesOnPage = 5;
    $categoriesQuery = "";
    $vegetarianQuery = "";
    $sortingQuery = "";
    $pageQuery = ""; // OFFSET в запросе
    if (isset($categories)) {
        if (sizeof($categories) > 1) {
            $categoriesQuery .= "(";
        }
        foreach ($categories as $value) {
            if (in_array($value, array("Wok", "Pizza", "Soup", "Dessert", "Drink"))) {
                $categoriesQuery .= "category = '$value' OR ";
            } else {
                setHTTPStatus("400", "There is no such dish category as '$value'");
                return;
            }
        }
        $categoriesQuery = substr($categoriesQuery, 0, -4);
        if (sizeof($categories) > 1) {
            $categoriesQuery .= ")";
        }
    } else {
        $categoriesQuery = "(category = 'Wok' OR category = 'Pizza' OR category = 'Soup' OR category = 'Dessert' OR category = 'Drink')";
    }
    if (isset($vegetarian)) {
        if (sizeof($vegetarian) != 1 || !($vegetarian[0] == "true" || $vegetarian[0] == "false")) {
            setHTTPStatus("400", "Parameter 'vegetarian' should be either 'true' or 'false'");
            return;
        } else {
            $vegetarian[0] = $vegetarian[0] == "true" ? 1 : 0;
            $vegetarianQuery = "vegetarian = '$vegetarian[0]'";
        }
    } else {
        $vegetarianQuery = "(vegetarian = '1' OR vegetarian = '0')";
    }
    if (isset($sorting)) {
        if (sizeof($sorting) != 1 || !in_array($sorting[0], array("NameAsc", "NameDesc", "PriceAsc", "PriceDesc", "RatingAsc", "RatingDesc"))) {
            setHTTPStatus("400", "Parameter 'sorting' should be one of the followings: NameAsc, NameDesc, PriceAsc, PriceDesc, RatingAsc, RatingDesc");
            return;
        } else {
            switch ($sorting[0]) {
                case "NameAsc":
                    $sortingQuery = "name ASC";
                    break;
                case "NameDesc":
                    $sortingQuery = "name DESC";
                    break;
                case "PriceAsc":
                    $sortingQuery = "price ASC";
                    break;
                case "PriceDesc":
                    $sortingQuery = "price DESC";
                    break;
                case "RatingAsc":
                    $sortingQuery = "rating ASC";
                    break;
                case "RatingDesc":
                    $sortingQuery = "rating DESC";
                    break;
            }
        }
    } else {
        $sortingQuery = "name ASC";
    }
    if (isset($page)) {
        if (sizeof($page) != 1 || !is_numeric($page[0]) || $page[0] < 1) {
            setHTTPStatus("400", "Parameter 'page' should be singular numeric value above 0");
            return;
        } else {
            $pageQuery = $dishesOnPage * ((int)$page[0] - 1);
        }
    } else {
        $page[0] = 1;
        $pageQuery = 0;
    }
    $query = "SELECT * FROM dish WHERE $categoriesQuery AND $vegetarianQuery ORDER BY $sortingQuery LIMIT $dishesOnPage OFFSET $pageQuery";
    include_once "api/dish_handler/helpers/update_ratings.php";
    updateRatings($query);
    $dishes = $Link->query($query)->fetch_all();
    if (sizeof($dishes) == 0) {
        setHTTPStatus("400", "Invalid value for attribute page");
    } else {
        include_once "api/dish_handler/helpers/transform_dishes.php";
        $jsonDishes = transformDishes($dishes);
        $dishesNumber = $Link->query("SELECT COUNT(*) FROM dish WHERE $categoriesQuery AND $vegetarianQuery ORDER BY $sortingQuery")->fetch_assoc()["COUNT(*)"];
        echo json_encode(array("dishes" => $jsonDishes, "pagination" => array("size" => $dishesOnPage, "count" => (int)(ceil((float)$dishesNumber / $dishesOnPage)), "current" => (int)$page[0])));
    }

}