<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/CustomersService.php";

// Class for handling requests to "api/Customer"

class CustomersAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {

        
        // If theres two parts in the path and the request method is GET 
        // it means that the client is requesting "api/Customers" and
        // we should respond by returning a list of all customers 
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAll();
        } 

        // If there's three parts in the path and the request method is GET
        // it means that the client is requesting "api/Customers/{something}".
        // In our API the last part ({something}) should contain the ID of a 
        // customer and we should respond with the customer of that ID
        else if ($this->path_count == 3 && $this->method == "GET") {
            $this->getById($this->path_parts[2]);
        }

        // If theres two parts in the path and the request method is POST 
        // it means that the client is requesting "api/Customers" and we
        // should get ths contents of the body and create a customer.
        else if ($this->path_count == 2 && $this->method == "POST") {
            $this->postOne();
        }

        // If theres two parts in the path and the request method is PUT 
        // it means that the client is requesting "api/Customers/{something}" and we
        // should get the contents of the body and update the customer.
        else if ($this->path_count == 3 && $this->method == "PUT") {
            $this->putOne($this->path_parts[2]);
        } 

        // If theres two parts in the path and the request method is DELETE 
        // it means that the client is requesting "api/Customers/{something}" and we
        // should get the ID from the URL and delete that customer.
        else if ($this->path_count == 3 && $this->method == "DELETE") {
            $this->deleteOne($this->path_parts[2]);
        } 
        
        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }

    // Gets all customers and sends them to the client as JSON
    private function getAll()
    {
        $customers = CustomersService::getAllCustomers();

        $this->sendJson($customers);
    }

    // Gets one and sends it to the client as JSON
    private function getById($id)
    {
        $customer = CustomersService::getCustomerById($id);

        if ($customer) {
            $this->sendJson($customer);
        }
        else {
            $this->notFound();
        }
    }

    // Gets the contents of the body and saves it as a customer by 
    // inserting it in the database.
    private function postOne()
    {
        $customer = new CustomerModel();

        $customer->customer_name = $this->body["customer_name"];
        $customer->birth_year = $this->body["birth_year"];

        $success = CustomersService::saveCustomer($customer);

        if($success){
            $this->created();
        }
        else{
            $this->error();
        }
    }

    // Gets the contents of the body and updates the customer
    // by sending it to the DB
    private function putOne($id)
    {
        $customer = new CustomerModel();

        $customer->customer_name = $this->body["customer_name"];
        $customer->birth_year = $this->body["birth_year"];

        $success = CustomersService::updateCustomerById($id, $customer);

        if($success){
            $this->ok();
        }
        else{
            $this->error();
        }
    }

    // Deletes the customer with the specified ID in the DB
    private function deleteOne($id)
    {
        $customer = CustomersService::getCustomerById($id);

        if($customer == null){
            $this->notFound();
        }

        $success = CustomersService::deleteCustomerById($id);

        if($success){
            $this->noContent();
        }
        else{
            $this->error();
        }
    }
}