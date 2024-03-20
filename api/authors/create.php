<?php
  // Set headers for CORS and JSON content
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
  if(empty($data->author)){
    // Return error message if parameters are missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set author property from request data
    $authors->author = $data->author;
    
    // Create author
    if($authors->create()) {
      // Retrieve created author
      $authors->read_single();
      // Construct response array
      $author_arr = array(
        'id' => $authors->id,
        'author' => $authors->author
      );
      // Return JSON response
      echo json_encode($author_arr);
    } else {
      // Return error message if author creation fails
      echo json_encode(
        array('message' => 'Missing Required Parameters')
      );
    }
  }
?>
