<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Category Object
$categories = new Category($db);

// Retrieve and decode JSON data sent in the request body
$data = json_decode(file_get_contents("php://input"));

// Check if both category and ID are provided
if(empty($data->category) || empty($data->id)) {
    // Return error if required parameters are missing
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
} else {
    // Set ID for update
    $categories->id = $data->id;

    // Check if category exists
    if($categories->read_single()) {
        // Assign updated category data
        $categories->category = $data->category;

        // Attempt to update category
        if($categories->update()) {
            // Retrieve updated category
            $categories->read_single();
            // Prepare response data
            $category_arr = array(
                'id' => $categories->id,
                'category' => $categories->category
            );
            // Output JSON
            echo json_encode($category_arr);
        } else {
            // Return error if update fails
            echo json_encode(
                array('message' => 'Category Not Updated')
            );
        }
    } else {
        // Return error if category ID not found
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    }
}
?>
