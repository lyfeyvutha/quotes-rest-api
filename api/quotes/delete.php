<?php 
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quotes = new Quote($db);

// Retrieve and decode JSON data sent in the request body
$data = json_decode(file_get_contents("php://input"));

// Check if ID is provided
if(empty($data->id)) {
    // Return error if ID is missing
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
} else {
    // Set ID for deletion
    $quotes->id = $data->id;
    
    // Check if quote exists
    if($quotes->read_single()) {
        // Delete quote
        if($quotes->delete()) {
            // Prepare response array
            $quote_arr = array('id' => $data->id);
            // Output JSON
            echo json_encode($quote_arr);
        } else {
            // Return error if deletion fails
            echo json_encode(
                array('message' => 'No Quotes Found')
            );
        }
    } else {
        // Return error if quote doesn't exist
        echo json_encode(
            array('message' => 'Quote Not Found')
        );
    }
}
?>
