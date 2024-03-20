<?php 
  // Set headers for CORS and JSON content
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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
  if(empty($data->author_id) || empty($data->category_id) || empty($data->quote) || empty($data->id)){
    // Return error message if parameters are missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID for update
    $quotes->id = $data->id;

    // Check if quote exists
    if($quotes->read_single()){
      // Assign updated values
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
        // Return error if author not found
        echo json_encode(
          array('message' => 'author_id Not Found')
        );
      }
      // Check if category exists
      elseif(!$categories->read_single()){
        // Return error if category not found
        echo json_encode(
          array('message' => 'category_id Not Found')
        );
      } 
      else{
        // Update quote
        if($quotes->update()) {
          // Re-read the updated quote details
          $quotes->read_single();
          // Construct response array
          $quote_arr = array(
            'id' => $quotes->id,
            'quote' => $quotes->quote,
            'author_id' => $quotes->author_name,
            'category_id' => $quotes->category_name
          );
          // Return JSON response indicating successful update
          echo json_encode($quote_arr);
        } else {
          // Return error message if quote update fails
          echo json_encode(
            array('message' => 'No Quotes Found')
          );
        }
      }
    } else {
      // Return error message if quote not found
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }
  }
?>
