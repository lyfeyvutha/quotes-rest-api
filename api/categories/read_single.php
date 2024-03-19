<?php

// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Category Object
$category = new Category($db);

// Get ID from request, terminate if not provided
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

// Retrieve category
$category->read_single();

// Create associative array for category details
$category_arr = array(
    'id' => $category->id,
    'category' => $category->category
);

// Check if category exists
if (!$category->category) {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
} else {
    // Convert array to JSON and output
    echo json_encode($category_arr);
}
