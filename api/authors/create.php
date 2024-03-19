<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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

// Check if author data is provided
if(empty($data->author)) {
    // Return error if required parameter is missing
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
} else {
    // Assign author data
    $authors->author = $data->author;
    
    // Create author
    if($authors->create()) {
        // Retrieve newly created author
        $authors->read_single();
        
        // Prepare author data for response
        $author_arr = array(
            'id' => $authors->id,
            'author' => $authors->author
        );

        // Output JSON
        echo json_encode($author_arr);
    } else {
        // Return error if creation fails
        echo json_encode(
            array('message' => 'Unable To Create Author')
        );
    }
}
?>
