<?php
// Set CORS headers and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database configuration and Author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate database connection
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$authorModel = new Author($db);

// Get the ID from the request parameters or terminate if not provided
$authorModel->id = isset($_GET['id']) ? $_GET['id'] : die();

// Read a single author
if ($authorModel->read_single()) {
    // Create an array for the author's data
    $authorArray = array(
        'id' => $authorModel->id,
        'author' => $authorModel->author
    );

    // Convert the array to JSON and output
    echo json_encode($authorArray);
} else {
    // No author found with the provided ID
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
}
?>
