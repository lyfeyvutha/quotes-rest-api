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

// Read all categories
$result = $categoryModel->read();

// Get the number of categories
$num = $result->rowCount();

// Check if any categories are found
if ($num > 0) {
    // Create an array to hold categories
    $categoryArray = array();

    // Loop through each category
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Extract category data
        extract($row);

        // Create an array for the category
        $categoryItem = array(
            'id' => $id,
            'category' => $category
        );

        // Push the category data to the array
        array_push($categoryArray, $categoryItem);
    }

    // Convert the array to JSON and output
    echo json_encode($categoryArray);
} else {
    // No categories found
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}
?>
