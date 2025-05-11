<?php
class Connect {
    // private $servername = "localhost"; // Default XAMPP server
    // private $username = "u611130114_arafat1";        // Default XAMPP username
    // private $password = "arafat@Rhru!!!1111";            // Default XAMPP password (empty)
    // private $dbname = "u611130114_arafat";         // Database name
    private $servername = "localhost"; // Default XAMPP server
    private $username = "root";        // Default XAMPP username
    private $password = "";            // Default XAMPP password (empty)
    private $dbname = "db_portfolio";         // Database name
    public $conn;                      // Connection object

    // Constructor to establish the connection
    public function __construct() {
        $this->connect();      // Establish server connection
        $this->checkDatabase(); // Check and create database if necessary
    }

    // Connect to the server
    private function connect() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);

        if (!$this->conn) {
            die("Server connection failed: " . mysqli_connect_error());
        }
        else {
            // echo 'connected';
        }
    }

    // Check if the database exists, if not, create it
    private function checkDatabase() {
        $sql = "SHOW DATABASES LIKE '$this->dbname'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            // Database does not exist, create it
            $this->createDatabase();
        }

        // Select the database
        if (!mysqli_select_db($this->conn, $this->dbname)) {
            die("Failed to select database: " . mysqli_error($this->conn));
        }
    }

    // Create the database
    private function createDatabase() {
        $sql = "CREATE DATABASE $this->dbname";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Database '$this->dbname' created successfully.<br>";
        } else {
            die("Error creating database: " . mysqli_error($this->conn));
        }
    }

    // Function to close the connection
    public function closeConnection() {
        if ($this->conn) {
            mysqli_close($this->conn);
            echo 'closed connection';
        }
    }
}
?>
