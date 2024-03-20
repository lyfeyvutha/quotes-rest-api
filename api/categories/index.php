<?php
// Set headers for CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Retrieve the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle preflight OPTIONS request
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}
else if ($method == 'GET') {
// Extract URL and retrieve the last segment
$requestUri = $_SERVER['REQUEST_URI'];
$urlSegments = explode('/', $requestUri);
$lastSegment = array_pop($urlSegments);

// Determine if it's a single read request or a general read request
$isSingleReadRequest = str_contains($lastSegment, '?id=');

// Choose the appropriate script to require based on the request type
if ($isSingleReadRequest) {
    require 'read_single.php'; // Handle single read request
} else {
    require 'read.php'; // Handle general read request
}

}
else if ($method == 'POST') {
    // Handle POST request
    require 'create.php';
}
else if ($method == 'PUT') {
    // Handle PUT request
    require 'update.php';
}
else if ($method == 'DELETE') {
    // Handle DELETE request
    require 'delete.php';
}
