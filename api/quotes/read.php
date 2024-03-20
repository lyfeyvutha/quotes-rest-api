<?php 
// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote Object
$quotes = new Quote($db);

// Extract parameter from URL
$param = basename($_SERVER['REQUEST_URI']);


// Check if author_id or category_id is present in the URL parameter
if (preg_match('/author_id=(\d+)/', $param, $matches)) {
  $quotes->author_id = $matches[1]; // Assign the matched author_id to the object property
}

if (preg_match('/category_id=(\d+)/', $param, $matches)) {
  $quotes->category_id = $matches[1]; // Assign the matched category_id to the object property
}

// Execute quote query
$result = $quotes->read();

// Get number of rows returned
$num = $result->rowCount();

// Check if any quotes found
if ($num > 0) {
    // Initialize array for quotes
    $quotes_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Create array for each quote item
        $quotes_item = array(
            'id' => $id,
            'quote' => html_entity_decode($quote),
            'author' => $author_id,
            'category' => $category_id
        );

        // Push quote item to array
        array_push($quotes_arr, $quotes_item);
    }

    // Convert array to JSON and output
    echo json_encode($quotes_arr);
} else {
    // Check if author_id or category_id provided
    if (!empty($quotes->author_id) && !empty($quotes->category_id)) {
        // No quotes found
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    } else {
        // Check if author_id provided
        if (!empty($quotes->author_id)) {
            $authors = new Author($db);
            $authors->id = $quotes->author_id;
            if (!$authors->read_single()) {
                // Author not found
                echo json_encode(
                    array('message' => 'author_id Not Found')
                );
            }
        } 
        // Check if category_id provided
        if (!empty($quotes->category_id)) {
            $categories = new Category($db);
            $categories->id = $quotes->category_id;
            if (!$categories->read_single()) {
                // Category not found
                echo json_encode(
                    array('message' => 'category_id Not Found')
                );
            }
        }
        // No quotes found
        if (empty($quotes->author_id) && empty($quotes->category_id)) {
            echo json_encode(
                array('message' => 'No Quotes Found')
            );
        }
    }
}
?>