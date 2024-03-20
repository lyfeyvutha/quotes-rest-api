<?php 
  // Set headers for CORS and JSON content
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include necessary files
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Instantiate database connection
  $database = new Database();
  $db = $database->connect();

  // Instantiate Quote object
  $quotes = new Quote($db);

  // Retrieve and decode JSON data sent in the request body
  $data = json_decode(file_get_contents("php://input"));

  // Check if required parameters are missing
  if(empty($data->author_id) || empty($data->category_id) || empty($data->quote)){
    // Return error message if parameters are missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set quote properties from request data
    $quotes->quote = $data->quote;
    $quotes->author_id = $data->author_id;
    $quotes->category_id = $data->category_id;
    
    // Instantiate Author and Category objects
    $authors = new Author($db);
    $authors->id = $quotes->author_id;
    $categories = new Category($db);
    $categories->id = $quotes->category_id;

    // Check if author exists
    if(!$authors->read_single()){
      // Return error message if author is not found
      echo json_encode(
        array('message' => 'author_id Not Found')
      );
    }
    // Check if category exists
    else if(!$categories->read_single()){
      // Return error message if category is not found
      echo json_encode(
        array('message' => 'category_id Not Found')
      );
    }
    else{
      // Create quote
      if($quotes->create()) {
        // Retrieve created quote
        $quotes->read_single();
        // Construct response array
        $quote_arr = array(
          'id' => $quotes->id,
          'quote' => $quotes->quote,
          'author_id' => $quotes->author_id,
          'category_id' => $quotes->category_id
        );
        // Return JSON response
        echo json_encode($quote_arr);
      } else {
        // Return error message if quote creation fails
        echo json_encode(
          array('message' => 'Quote Not Created')
        );
      }
    }
  }
?>
