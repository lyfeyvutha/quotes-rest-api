<?php

class Author {
    // Database connection
    private $conn;
    private $table = "authors";

    // Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all authors
    public function read() {
        // Create query
        $query = 'SELECT id, author FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single author by ID
    public function read_single() {
        // Create query
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        // Check if author exists
        if ($stmt->rowCount() == 0) {
            echo json_encode(array('message' => 'author_id Not Found'));
            exit();
        }

        // Fetch the author details
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->author = $row['author'];
    }

    // Create author
    public function create() {
        // Define the SQL query to insert a new author
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id';
  
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($query);
  
        // Clean data to prevent SQL injection
        $this->author = htmlspecialchars(strip_tags($this->author));
    
        // Bind the author data to the prepared statement
        $stmt->bindParam(':author', $this->author);
    
        // Execute the SQL query
        if($stmt->execute()) {
        // Fetch the newly generated ID after insertion
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        // Return true to indicate successful creation
        return true;
        }
    
        // If execution fails, print the error message
        printf("Error: %s.\n", $stmt->error);
    
        // Return false to indicate failure
        return false;
    }
  
    // Update an existing author
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':author', $this->author);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
// Delete an author
public function delete() {
    // Define the SQL query to delete an author
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($query);

    // Clean the data to prevent SQL injection
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind the author ID data to the prepared statement
    $stmt->bindParam(':id', $this->id);

    // Execute the SQL query
    if ($stmt->execute()) {
        // Return true to indicate successful deletion
        return true;
    }

    // If execution fails, print the error message
    printf("Error: %s.\n", $stmt->error);

    // Return false to indicate failure
    return false;
}

}
?>
