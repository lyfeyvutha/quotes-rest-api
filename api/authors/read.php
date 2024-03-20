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

// Fetch authors from the database
$result = $authorModel->read();

// Get the number of rows returned
$numRows = $result->rowCount();

// Check if any authors are found
if ($numRows > 0) {
    // Initialize an array to store authors
    $authorsArray = array();

    // Loop through the results and extract data
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Create an array for each author
        $authorItem = array(
            'id' => $id,
            'author' => $author
        );

        // Add the author data to the authors array
        array_push($authorsArray, $authorItem);
    }

    // Convert the authors array to JSON and output
    echo json_encode($authorsArray);
} else {
    // No authors found
    echo json_encode(
        array('message' => 'No Authors Found')
    );
}
?>
