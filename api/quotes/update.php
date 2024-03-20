<?php 
  // Headers
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
  $quote = new Quote($db);

  // Get raw quote data
  $data = json_decode(file_get_contents("php://input"));

  // Check for missing parameters
  if(empty($data->author_id) || empty($data->category_id) || empty($data->quote) || empty($data->id)){
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID to update
    $quote->id = $data->id;

    // Check if quote exists
    if($quote->read_single()){
      // Set quote properties
      $quote->quote = $data->quote;
      $quote->author_id = $data->author_id;
      $quote->category_id = $data->category_id;

      // Instantiate Author and Category objects
      $author = new Author($db);
      $author->id = $quote->author_id;
      $category = new Category($db);
      $category->id = $quote->category_id;

      // Check if author exists
      if(!$author->read_single()){
        echo json_encode(
          array('message' => 'author_id Not Found')
        );
      }
      // Check if category exists
      else if(!$category->read_single()){
        echo json_encode(
          array('message' => 'category_id Not Found')
        );
      }
      else{
        // Update quote
        if($quote->update()) {
          // Retrieve updated quote
          $quote->read_single();
          // Format data into an array
          $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
          );
          // Convert to JSON and output
          echo json_encode($quote_arr);
        } else {
          echo json_encode(
            array('message' => 'No Quotes Found')
          );
        }
      }
    }
    else{
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }
  }
?>
