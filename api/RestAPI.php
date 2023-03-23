<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Base class for all API classes to inherit from.
// Includes functions for sending response as JSON
// as well as parsing the request.

class RestAPI
{

    protected $path_parts, $path_count, $query_params, $method, $body;

    // Gets data from the url and sets the protected properties
    // so that any class inheriting from this can read and handle
    // the request appropriately
    public function __construct($path_parts, $query_params)
    {
        
        // Set the other protected properties
        $this->path_parts = $this->removeEmptyStrings($path_parts);
        $this->query_params = $query_params;
        $this->method = $_SERVER["REQUEST_METHOD"];

        // Count the number of "parts" in the path
        // Example: "api/Customers" is 2 parts and
        // "api/Customers/5" is 3 parts
        $this->path_count = count($this->path_parts);

        $this->parseBody();
    }

    // Sends the content of $response as JSON and ends execution
    // $status is the status code to send (200 is default and means "OK") 
    protected function sendJson($response, $status = 200)
    {
        http_response_code($status);

        header("Content-Type: application/json");

        echo json_encode($response);

        die();
    }


    // Preset response for OK-response (200)
    protected function ok(){
        $this->sendJson("OK");
    }

    // Preset response for no content (204)
    protected function noContent(){
        $this->sendJson("", 204);
    }

    // Preset response for if a resource is not found
    protected function notFound(){
        $this->sendJson("Not found", 404);
    }

    // Preset response for if a resource is created
    protected function created(){
        $this->sendJson("Created", 201);
    }

    // Preset response for general server error
    protected function error(){
        $this->sendJson("Error", 500);
    }


    // Parses the body as JSON so classes inheriting from this 
    // can access the body variables using $this->body
    private function parseBody()
    {
        $input = file_get_contents("php://input");

        if(strlen($input) > 0){
            $this->body = json_decode($input, true);
        }
        
    }

    private function removeEmptyStrings($arr) {
        return array_filter($arr, function($str) {
            return trim($str) !== '';
        });
    }
    
}