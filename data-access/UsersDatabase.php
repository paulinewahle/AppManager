<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/CustomerModel.php";

class CustomersDatabase extends Database
{
    private $table_name = "customers";
    private $id_name = "customer_id";

    // Get one customer by using the inherited function getOneRowByIdFromTable
    public function getOne($customer_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $customer_id);

        $customer = $result->fetch_object("CustomerModel");

        return $customer;
    }


    // Get all customers by using the inherited function getAllRowsFromTable
    public function getAll()
    {
        $result = $this->getAllRowsFromTable($this->table_name);

        $customers = [];

        while ($customer = $result->fetch_object("CustomerModel")) {
            $customers[] = $customer;
        }

        return $customers;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(CustomerModel $customer)
    {
        $query = "INSERT INTO customers (customer_name, birth_year) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("si", $customer->customer_name, $customer->birth_year);

        $success = $stmt->execute();

        return $success;
    }

    // Update one by creating a query and using the inherited $this->conn 
    public function updateById($customer_id, CustomerModel $customer)
    {
        $query = "UPDATE customers SET customer_name=?, birth_year=? WHERE customer_id=?;";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sii", $customer->customer_name, $customer->birth_year, $customer_id);

        $success = $stmt->execute();

        return $success;
    }

    // Delete one customer by using the inherited function deleteOneRowByIdFromTable
    public function deleteById($customer_id)
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $customer_id);

        return $success;
    }
}