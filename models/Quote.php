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
        // Define the SQL query to insert a new quote
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';

        // Prepare the SQL statement
        $stmt = $this->conn->prepare($query);

        // Clean data to prevent SQL injection
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind the data to the prepared statement
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute the SQL query
        if ($stmt->execute()) {
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

// Update a quote
public function update() {
    // Define the SQL query to update a quote
    $query = 'UPDATE ' . $this->table . '
                SET quote = :quote, author_id = :author_id, category_id = :category_id
                WHERE id = :id';

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($query);

    // Clean data to prevent SQL injection
    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind the data to the prepared statement
    $stmt->bindParam(':quote', $this->quote);
    $stmt->bindParam(':author_id', $this->author_id);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);

    // Execute the SQL query
    if ($stmt->execute()) {
        // Return true to indicate successful update
        return true;
    }

    // If execution fails, print the error message
    printf("Error: %s.\n", $stmt->error);

    // Return false to indicate failure
    return false;
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