<?php 

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for apps-table in database

class AppModel{
    public $app_id;
    public $app_name;
    public $description;
    public $price;
}