<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Object
$authors = new Author($db);

// Retrieve and decode JSON data sent in the request body
$data = json_decode(file_get_contents("php://input"));

// Check if both author and ID are provided
if(empty($data->author) || empty($data->id)) {
    // Return error if required parameters are missing
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
} else {
    // Set ID for update
    $authors->id = $data->id;

    // Check if author exists
    if($authors->read_single()) {
        // Assign updated author data
        $authors->author = $data->author;

        // Attempt to update author
        if($authors->update()) {
            // Retrieve updated author
            $authors->read_single();
            // Prepare response data
            $author_arr = array(
                'id' => $authors->id,
                'author' => $authors->author
            );
            // Output JSON
            echo json_encode($author_arr);
        } else {
            // Return error if update fails
            echo json_encode(
                array('message' => 'Author Not Updated')
            );
        }
    } else {
        // Return error if author ID not found
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }
}
?>
