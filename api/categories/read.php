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
$categories = new Category($db);

// Execute category read query
$result = $categories->read();

// Get number of rows returned
$num = $result->rowCount();

// Check if any categories found
if ($num > 0) {
    // Initialize array for categories
    $cat_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Create array for each category item
        $cat_item = array(
            'id' => $id,
            'category' => $category
        );

        // Push category item to array
        array_push($cat_arr, $cat_item);
    }

    // Convert array to JSON and output
    echo json_encode($cat_arr);
} else {
    // No categories found
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}
