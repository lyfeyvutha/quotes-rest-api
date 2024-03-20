<?php
// Set CORS headers and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog quote object
$quotes = new Quote($db);

// Get the request URI and extract the parameter
$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);
$param = array_pop($url);

// Set author_id or category_id based on the parameter
if (str_contains($param, 'author_id')) {
    $quotes->author_id = $_GET['author_id'] ?? NULL;
}
if (str_contains($param, 'category_id')) {
    $quotes->category_id = $_GET['category_id'] ?? NULL;
}

// Retrieve quotes from the database
$result = $quotes->read();

// Get the number of quotes
$num = $result->rowCount();

// Check if any quotes are found
if ($num > 0) {
    // Array to hold quotes
    $quotes_arr = array();

    // Loop through each quote
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Construct quote item array
        $quote_item = array(
            'id' => $id,
            'quote' => html_entity_decode($quote),
            'author' => $author_name,
            'category' => $category_name
        );

        // Push quote item to "data"
        array_push($quotes_arr, $quote_item);
    }

    // Convert the array to JSON and output
    echo json_encode($quotes_arr);
} else {
    // Check if author_id or category_id is provided
    if (!empty($quotes->author_id) || !empty($quotes->category_id)) {
        // Instantiate Author and Category objects
        $authors = new Author($db);
        $authors->id = $quotes->author_id;
        $categories = new Category($db);
        $categories->id = $quotes->category_id;

        // Check if author_id exists
        if (!$authors->read_single()) {
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
        } else if (!$categories->read_single()) { // Check if category_id exists
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
        } else {
            echo json_encode(
                array('message' => 'No Quotes Found')
            );
        }
    } else {
        // No quotes found
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
}
?>
