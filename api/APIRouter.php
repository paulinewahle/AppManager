<?php
// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/UsersAPI.php";
require_once __DIR__ . "/AppsAPI.php";

// Class for routing all our API requests

class APIRouter{

    private $path_parts, $query_params;
    private $routes = [];

    public function __construct($path_parts, $query_params)
    {
        $this->routes = [
            // Whenever we call "api/users", load the UsersAPI class
            "users" => "UsersAPI",
            "apps" => "AppsAPI"
        ];

        $this->path_parts = $path_parts;
        $this->query_params = $query_params;
    }

    public function handleRequest(){

        // Get the requested resource from the URL such as "Users" or "Apps"
        $resource = strtolower($this->path_parts[1]);

        // Cet the class specified in the routes
        $route_class = $this->routes[$resource];

        // Create a new object from the resource class
        $route_object = new $route_class($this->path_parts, $this->query_params);

        // Handle the request
        $route_object->handleRequest();
    }
}