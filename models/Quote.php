<?php 
class Quote {
    // Database connection
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $quote;
    public $category_id;
    public $author_id;

    // Explicitly declare properties for author name and category name
    public $author_name;
    public $category_name;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get quotes
    public function read() {
        // Create base query
        $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                    FROM ' . $this->table . ' p
                    JOIN authors a ON a.id = p.author_id 
                    JOIN categories c ON c.id = p.category_id ';

        // Conditionally add author and category filters
        if (!empty($this->author_id) && !empty($this->category_id)) {
            $query .= 'WHERE p.author_id = :author_id AND p.category_id = :category_id ';
        } elseif (!empty($this->author_id)) {
            $query .= 'WHERE p.author_id = :author_id ';
        } elseif (!empty($this->category_id)) {
            $query .= 'WHERE p.category_id = :category_id ';
        }

        // Add order by clause
        $query .= 'ORDER BY p.id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters if necessary
        if (!empty($this->author_id) && !empty($this->category_id)) {
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
        } elseif (!empty($this->author_id)) {
            $stmt->bindParam(':author_id', $this->author_id);
        } elseif (!empty($this->category_id)) {
            $stmt->bindParam(':category_id', $this->category_id);
        }

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single quote by ID
    public function read_single() {
        // Create query
        $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                    FROM ' . $this->table . ' p
                    JOIN authors a ON a.id = p.author_id 
                    JOIN categories c ON c.id = p.category_id 
                    WHERE p.id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute query
        $stmt->execute();

        // Fetch the quote details
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if quote exists
        if (!$row) {
            return false;
        }

        // Set properties
        $this->id = $row['id'];
        $this->quote = $row['quote'];
        $this->author_name = $row['author_name'];
        $this->category_name = $row['category_name'];

        return true;
    }

    // Create a new quote
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            // Fetch the inserted ID
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Update an existing quote
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

// Delete a quote
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