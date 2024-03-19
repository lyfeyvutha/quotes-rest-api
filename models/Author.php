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

    // Create a new author
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
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
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
}
?>
