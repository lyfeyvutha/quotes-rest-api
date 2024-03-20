<?php 
  class Database {
        // DB Params
        private $conn;
        private $host;
        private $port;
        private $dbname;
        private $username;
        private $password;
    

        public function __construct() {
            $this->username= getenv('DBUSERNAME');
            $this->password= getenv('DBPASSWORD');
            $this->dbname= getenv('DBNAME');
            $this->host= getenv('DBHOST');
            $this->port= getenv('DBPORT');
        }
    // DB Connect
    public function connect() {
        if($this->conn) {
          return $this->conn; // connection already exists, return it
        }
        else{
          $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
          try { 
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
          } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
          }
        }
      }
    }