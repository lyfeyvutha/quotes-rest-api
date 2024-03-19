<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Retrieve HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle preflight OPTIONS request
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}
// Handle GET request
else if ($method == 'GET') {
// Extract the URL parameters
$requestUri = $_SERVER['REQUEST_URI'];
$urlSegments = explode('/', $requestUri);
$lastSegment = array_pop($urlSegments);

// Check if it's a read_single or read request
$isReadSingleRequest = str_contains($lastSegment, '?id=');

// Determine which script to require based on the request type
if ($isReadSingleRequest) {
    require 'read_single.php'; // Handle single read request
} else {
    require 'read.php'; // Handle general read request
}

}
// Handle POST request
else if ($method == 'POST') {
    require 'create.php';
}
// Handle PUT request
else if ($method == 'PUT') {
    require 'update.php';
}
// Handle DELETE request
else if ($method == 'DELETE') {
    require 'delete.php';
}
// Handle other types of requests by defaulting to read
else {
    require 'read.php';
}
?>
