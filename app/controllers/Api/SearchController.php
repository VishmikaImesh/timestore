<?php

require_once("../app/model/search.php");

class SearchController
{
    public function search()
    {
        // Set JSON content type header
        header('Content-Type: application/json');
        
        // Validate HTTP method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["state" => false, "message" => "Method not allowed. Use POST"]);
            return;
        }

        // Check if POST data exists
        if (empty($_POST)) {
            http_response_code(400);
            echo json_encode(["state" => false, "message" => "No data provided"]);
            return;
        }

        try {
            $search = new search();
            $search->search($_POST);
        } catch (Exception $e) {
            http_response_code(500);
            error_log("Search API Error: " . $e->getMessage());
            echo json_encode(["state" => false, "message" => "An error occurred while processing your search"]);
        }
    }
}
