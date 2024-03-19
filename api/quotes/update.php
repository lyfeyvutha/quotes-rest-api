<?php 

// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Retrieve and decode JSON data sent in the request body
$data = json_decode(file_get_contents("php://input"));

// Check if all required parameters are provided
if (!isset($data->author_id) || !isset($data->category_id) || !isset($data->id) || !isset($data->quote)) {
    // Return error if any required parameter is missing
    echo json_encode(array('message' => "Missing Required Parameters"));
    exit(); // Stop script execution
}

// Set properties for quote update
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Update quote
if($quote->update()) {
    // Return updated quote details if update is successful
    echo json_encode(array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    ));
} else {
    // Return error message if update fails
    echo json_encode(array('message' => 'Quote Not Updated'));
}
?>
