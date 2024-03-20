<?php 
// Set CORS headers and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog quote object
$quotes = new Quote($db);

// Get the quote ID from the query parameters
$quotes->id = isset($_GET['id']) ? $_GET['id'] : NULL;

// Read the single quote
if ($quotes->read_single()) {
    // Create an array to hold the quote details
    $quotes_arr = array(
        'id' => $quotes->id,
        'quote' => $quotes->quote,
        'author' => $quotes->author_name,
        'category' => $quotes->category_name
    );

    // Convert the array to JSON and output
    echo json_encode($quotes_arr);
} else {
    // No quotes found
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
?>
