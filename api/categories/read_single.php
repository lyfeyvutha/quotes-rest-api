<?php
// Set CORS headers and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database configuration and Category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate database connection
$database = new Database();
$db = $database->connect();

// Instantiate Category object
$categoryModel = new Category($db);

// Get the category ID from the request parameters
$categoryModel->id = isset($_GET['id']) ? $_GET['id'] : die();

// Read single category
if ($categoryModel->read_single()) {
    // Create an array to hold category data
    $categoryArray = array(
        'id' => $categoryModel->id,
        'category' => $categoryModel->category
    );

    // Convert the array to JSON and output
    echo json_encode($categoryArray);
} else {
    // No category found with the given ID
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}
?>
