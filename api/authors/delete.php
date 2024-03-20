<?php
  // Set headers for CORS
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
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

  // Check if required parameter is missing
  if(empty($data->id)){
    // Return error message if parameter is missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID for deletion
    $authors->id = $data->id;
    
    // Check if author exists
    if($authors->read_single()){
      // Attempt to delete author
      if($authors->delete()) {
        // Construct response array
        $author_arr = array(
          'id' => $data->id
        );
        // Return JSON response indicating successful deletion
        echo json_encode($author_arr);
      } else {
        // Return error message if author deletion fails
        echo json_encode(
          array('message' => 'Author Not Deleted')
        );
      }
    }
    else{
      // Return error message if author_id is not found
      echo json_encode(
        array('message' => 'author_id Not Found')
      );
    }
  }
?>
