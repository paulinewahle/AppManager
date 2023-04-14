<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../data-access/AppsDatabase.php";

class AppsService{

    // Get one customer by creating a database object 
    // from data-access layer and calling its getOne function.
    public static function getAppById($id){
        $apps_database = new AppsDatabase();

        $app = $apps_database->getOne($id);

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is apps password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to apps calling the API

        return $app;
    }

    // Get all customers by creating a database object 
    // from data-access layer and calling its getAll function.
    public static function getAllApps(){
        $apps_database = new AppsDatabase();

        $apps = $apps_database->getAll();

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is apps password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to apps calling the API

        return $apps;
    }

    // Save a customer to the database by creating a database object 
    // from data-access layer and calling its insert function.
    public static function saveApp(AppModel $app){
        $apps_database = new AppsDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $apps_database->insert($app);

        return $success;
    }

    // Update the customer in the database by creating a database object 
    // from data-access layer and calling its update function.
    public static function updateAppsById($app_id, AppsModel $app){
        $apps_database = new AppssDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $apps_database->updateById($app_id, $app);

        return $success;
    }

    // Delete the customer from the database by creating a database object 
    // from data-access layer and calling its delete function.
    public static function deleteAppById($app_id){
        $apps_database = new AppsDatabase();

        // If you need to validate data or control what 
        // gets deleted from the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $apps_database->deleteById($app_id);

        return $success;
    }
}