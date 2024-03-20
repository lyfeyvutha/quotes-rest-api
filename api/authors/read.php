<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate Database and Connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Model
$authors = new Author($db);

// Execute read query to fetch authors
$result = $authors->read();

// Get the number of rows returned
$num = $result->rowCount();

// Check if any authors were found
if ($num > 0) {
    $authors_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array(
            'id' => $id,
            'author' => $author
        );
        array_push($authors_arr, $author_item);
    }

    // Convert array to JSON and output
    echo json_encode($authors_arr);
} else {
    // No authors found
    echo json_encode(
        array('message' => 'No Authors Found')
    );
}
