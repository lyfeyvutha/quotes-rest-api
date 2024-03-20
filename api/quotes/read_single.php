<?php 
// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote Object
$quotes = new Quote($db);

// Get ID from request, set to NULL if not provided
$quotes->id = isset($_GET['id']) ? $_GET['id'] : NULL;

// Read single quote
if ($quotes->read_single()) {
    // Create array for quote details
    $quotes_arr = array(
        'id' => $quotes->id,
        'quote' => $quotes->quote,
        'author' => $quotes->author_id,
        'category' => $quotes->category_id
    );
    
    // Convert array to JSON and output
    echo json_encode($quotes_arr);
} else {
    // No quotes found
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}