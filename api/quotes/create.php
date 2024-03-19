<?php 
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quotes = new Quote($db);

// Retrieve and decode JSON data sent in the request body
$data = json_decode(file_get_contents("php://input"));

// Check if all required parameters are provided
if(empty($data->author_id) || empty($data->category_id) || empty($data->quote)) {
    // Return error if required parameters are missing
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );

} else {
    // Assign quote data
    $quotes->quote = $data->quote;
    $quotes->author_id = $data->author_id;
    $quotes->category_id = $data->category_id;
    
    // Instantiate Author and Category objects
    $authors = new Author($db);
    $authors->id = $quotes->author_id;
    $categories = new Category($db);
    $categories->id = $quotes->category_id;
    
    // Check if author exists
    if(!$authors->read_single()) {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    } elseif (!$categories->read_single()) {
        // Check if category exists
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    } else {
        // Create quote
        if($quotes->create()) {
            // Retrieve created quote
            $quotes->read_single();
            // Prepare quote data for response
            $quote_arr = array(
                'id' => $quotes->id,
                'quote' => $quotes->quote,
                'author_id' => $quotes->author_id,
                'category_id' => $quotes->category_id
            );
            // Output JSON
            echo json_encode($quote_arr);
        } else {
            // Return error if creation fails
            echo json_encode(
                array('message' => 'Quote Not Created')
            );
        }
    }
}
?>
