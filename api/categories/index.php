<?php
// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Get the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle OPTIONS request for preflight CORS
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
} elseif ($method === 'GET') {
    // Process GET request
    $url = $_SERVER['REQUEST_URI'];
    $urlParts = explode('/', $url);
    $lastParam = array_pop($urlParts);
    if (str_contains($lastParam, '?id=')) {
        require 'read_single.php';
    } else {
        require 'read.php';
    }
} elseif ($method === 'POST') {
    // Process POST request
    require 'create.php';
} elseif ($method === 'PUT') {
    // Process PUT request
    require 'update.php';
} elseif ($method === 'DELETE') {
    // Process DELETE request
    require 'delete.php';
}
?>
