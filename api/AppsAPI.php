<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/AppsService.php";

// Class for handling requests to "api/App"

class AppsAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {

        
        // If theres two parts in the path and the request method is GET 
        // it means that the client is requesting "api/Apps" and
        // we should respond by returning a list of all apps 
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAll();
        } 

        // If there's three parts in the path and the request method is GET
        // it means that the client is requesting "api/Apps/{something}".
        // In our API the last part ({something}) should contain the ID of a 
        // app and we should respond with the app of that ID
        else if ($this->path_count == 3 && $this->method == "GET") {
            $this->getById($this->path_parts[2]);
        }

        // If theres two parts in the path and the request method is POST 
        // it means that the client is requesting "api/Apps" and we
        // should get ths contents of the body and create a app.
        else if ($this->path_count == 2 && $this->method == "POST") {
            $this->postOne();
        }

        // If theres two parts in the path and the request method is PUT 
        // it means that the client is requesting "api/Apps/{something}" and we
        // should get the contents of the body and update the app.
        else if ($this->path_count == 3 && $this->method == "PUT") {
            $this->putOne($this->path_parts[2]);
        } 

        // If theres two parts in the path and the request method is DELETE 
        // it means that the client is requesting "api/Apps/{something}" and we
        // should get the ID from the URL and delete that app.
        else if ($this->path_count == 3 && $this->method == "DELETE") {
            $this->deleteOne($this->path_parts[2]);
        } 
        
        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }

    // Gets all apps and sends them to the client as JSON
    private function getAll()
    {
        $apps = AppsService::getAllApps();

        $this->sendJson($apps);
    }

    // Gets one and sends it to the client as JSON
    private function getById($id)
    {
        $app = AppsService::getAppById($id);

        if ($app) {
            $this->sendJson($app);
        }
        else {
            $this->notFound();
        }
    }

    // Gets the contents of the body and saves it as a app by 
    // inserting it in the database.
    private function postOne()
    {
        $app = new AppModel();

        $app->app_name = $this->body["app_name"];
        $app->description = $this->body["description"];
        $app->price = $this->body["price"];
        

        $success = AppsService::saveApp($app);

        if($success){
            $this->created();
        }
        else{
            $this->error();
        }
    }

    // Gets the contents of the body and updates the app
    // by sending it to the DB
    private function putOne($id)
    {
        $app = new AppModel();

        $app->app_name = $this->body["app_name"];
        $app->birth_year = $this->body["birth_year"];

        $success = AppsService::updateAppById($id, $app);

        if($success){
            $this->ok();
        }
        else{
            $this->error();
        }
    }

    // Deletes the app with the specified ID in the DB
    private function deleteOne($id)
    {
        $app = AppsService::getAppById($id);

        if($app == null){
            $this->notFound();
        }

        $success = AppsService::deleteAppById($id);

        if($success){
            $this->noContent();
        }
        else{
            $this->error();
        }
    }
}