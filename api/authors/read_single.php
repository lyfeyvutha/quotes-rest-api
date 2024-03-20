<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate Database and Connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Object
$author = new Author($db);

// Check if ID is set, otherwise terminate with an error message
$author->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get Author
$author->read_single();

// Create associative array for author details
$author_arr = array(
    'id' => $author->id,
    'author' => $author->author
);

// Check if author exists
if (!$author->author) {
    echo json_encode(
        array('message' => 'author_id Not Found.')
    );
} else {
    // Convert array to JSON and output
    echo json_encode($author_arr);
}