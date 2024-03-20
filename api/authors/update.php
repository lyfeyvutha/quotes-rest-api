<?php
  // Set headers for CORS and JSON content
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include necessary files
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate database connection
  $database = new Database();
  $db = $database->connect();

  // Instantiate Author object
  $authors = new Author($db);

  // Retrieve and decode JSON data sent in the request body
  $data = json_decode(file_get_contents("php://input"));

  // Check if required parameters are missing
  if(empty($data->author) || empty($data->id)){
    // Return error message if parameters are missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID for update
    $authors->id = $data->id;

    // Check if author exists
    if($authors->read_single()){
      // Update author's details
      $authors->author = $data->author;

      // Attempt to update author
      if($authors->update()) {
        // Re-read the updated author's details
        $authors->read_single();
        // Construct response array
        $author_arr = array(
          'id' => $authors->id,
          'author' => $authors->author
        );
        // Return JSON response indicating successful update
        echo json_encode($author_arr);
      } else {
        // Return error message if author update fails
        echo json_encode(
          array('message' => 'Author Not Updated')
        );
      }
    } else {
      // Return error message if author_id is not found
      echo json_encode(
        array('message' => 'author_id Not Found')
      );
    }
  }
?>
