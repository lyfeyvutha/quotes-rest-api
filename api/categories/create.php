<?php
  // Set headers for CORS and JSON content
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include necessary files
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate database connection
  $database = new Database();
  $db = $database->connect();

  // Instantiate Category object
  $categories = new Category($db);

  // Retrieve and decode JSON data sent in the request body
  $data = json_decode(file_get_contents("php://input"));

  // Check if required parameters are missing
  if(empty($data->category)){
    // Return error message if parameters are missing
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set category property from request data
    $categories->category = $data->category;

    // Create category
    if($categories->create()) {
      // Retrieve created category
      $categories->read_single();
      // Construct response array
      $category_arr = array(
        'id' => $categories->id,
        'category' => $categories->category
      );
      // Return JSON response
      echo json_encode($category_arr);
    } else {
      // Return error message if category creation fails
      echo json_encode(
        array('message' => 'Missing Required Parameters')
      );
    }
  }
?>
