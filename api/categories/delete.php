<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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

// Check if ID is provided
if(empty($data->id)) {
    // Return error if required parameter is missing
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
} else {
    // Set ID for deletion
    $categories->id = $data->id;

    // Check if category exists
    if($categories->read_single()) {
        // Attempt to delete category
        if($categories->delete()) {
            // Prepare response data
            $category_arr = array(
                'id' => $data->id
            );
            // Output JSON
            echo json_encode($category_arr);
        } else {
            // Return error if deletion fails
            echo json_encode(
                array('message' => 'Category Not Deleted')
            );
        }
    } else {
        // Return error if category ID not found
        echo json_encode(
            array('message' => 'category_id not found')
        );
    }
}
?>
