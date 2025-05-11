<?php
include_once('connect.php');  // Include the connection file

class File
{

    // Class properties (variables) corresponding to table columns
    public $file_id;
    public $status = 'INT DEFAULT 1';  // Default data type for status
    public $file_owner = 'INT DEFAULT 0';  // Default data type for file_owner
    public $file_name = 'TEXT';  // Default data type for file_name
    public $file_original_name = 'TEXT';  // Default data type for file_name
    public $created = 'DATETIME';  // Default data type for created
    public $modified = 'DATETIME';  // Default data type for modified

    private $conn; // Database connection
    private $db;

    // Constructor to initialize the database connection
    public function __construct()
    {
        // Create a database connection
        $this->db = new Connect();
        $this->conn = $this->db->conn;
    }

    // ************CREATE

    // Create tbl_file if it doesn't exist
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_file (
            file_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_file created successfully or already exists.<br>";
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }

        // Call the functions to add the columns after creating the table
        $this->addStatusColumn();
        $this->addFileOwnerColumn();
        $this->addFileNameColumn();
        $this->addFileOriginalNameColumn();
        $this->addCreatedColumn();
        $this->addModifiedColumn();
    }

    // Add the 'status' column if it doesn't exist
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS status {$this->status}";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully (if it did not exist).<br>";
        }
    }

    // Add the 'file_owner' column if it doesn't exist
    public function addFileOwnerColumn()
    {
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS file_owner {$this->file_owner}";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'file_owner': " . mysqli_error($this->conn));
        } else {
            echo "Column 'file_owner' added successfully (if it did not exist).<br>";
        }
    }

    // Add the 'file_name' column if it doesn't exist
    public function addFileNameColumn()
    {
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS file_name {$this->file_name}";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'file_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'file_name' added successfully (if it did not exist).<br>";
        }
    }
    
    // Add the 'file_name' column if it doesn't exist
    public function addFileOriginalNameColumn()
    {
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS file_original_name {$this->file_original_name}";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'file_original_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'file_original_name' added successfully (if it did not exist).<br>";
        }
    }

    // Add the 'created' column if it doesn't exist
    public function addCreatedColumn()
    {
        // SQL query to add the 'created' column with the CURRENT_TIMESTAMP behavior
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully (if it did not exist).<br>";
        }
    }


    // Add the 'modified' column if it doesn't exist
    public function addModifiedColumn()
    {
        // SQL query to add the 'modified' column with the CURRENT_TIMESTAMP behavior
        $sql = "ALTER TABLE tbl_file ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully (if it did not exist).<br>";
        }
    }

    // function to insert a record into the tbl_file 
    // table using the class variables and return the generated file_id
    public function insertFile()
    {
        // Insert query
        $sql = "INSERT INTO tbl_file (status, file_owner, file_name, file_original_name) 
                VALUES ($this->status,$this->file_owner, '$this->file_name', '$this->file_original_name')";

        // Execute the query
        if (mysqli_query($this->conn, $sql)) {
            // Get the last inserted file_id (AUTO_INCREMENT value)
            $last_id = mysqli_insert_id($this->conn);
            $this->file_id = $last_id;
            // echo "New record created successfully. Last inserted ID is: " . $last_id . "<br>";
            return $last_id;
        } else {
            // echo "Error inserting record: " . mysqli_error($this->conn) . "<br>";
            return false;
        }
    }

    // ---------------READ
    public function setValuesById()
    {
        $sql = "SELECT * FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            // Set class variables based on the fetched row
            $this->status = $row['status'];
            $this->file_owner = $row['file_owner'];
            $this->file_name = $row['file_name'];
            $this->file_original_name = $row['file_original_name'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            // echo "Values set successfully from the database.<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getStatus()
    {
        $sql = "SELECT status FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->status = $row['status'];
            // echo "Status: " . $this->status . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getFileOwner()
    {
        $sql = "SELECT file_owner FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->file_owner = $row['file_owner'];
            // echo "File Owner: " . $this->file_owner . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getFileName()
    {
        $sql = "SELECT file_name FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->file_name = $row['file_name'];
            // echo "File Name: " . $this->file_name . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getFileOriginalName()
    {
        $sql = "SELECT file_extension FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->file_original_name = $row['file_original_name'];
            // echo "File Original Name: " . $this->file_original_name . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getCreated()
    {
        $sql = "SELECT created FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->created = $row['created'];
            // echo "Created: " . $this->created . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getModified()
    {
        $sql = "SELECT modified FROM tbl_file WHERE file_id = $this->file_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->modified = $row['modified'];
            // echo "Modified: " . $this->modified . "<br>";
        } else {
            // echo "Error: No record found for file_id: $this->file_id.<br>";
        }
    }

    public function getIdsByStatus()
    {
        $sql = "SELECT file_id FROM tbl_file WHERE status = '$this->status'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Fetch all file_id values
            $ids = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $ids[] = $row['file_id'];
            }

            return $ids;
        } else {
            // echo "No records found with status '$this->status'.<br>";
            return [];
        }
    }

    // -------------------UPDATE
    public function updateFile()
    {
        $sql = "UPDATE tbl_file SET 
                    status = '$this->status', 
                    file_owner = '$this->file_owner', 
                    file_name = '$this->file_name',
                    file_original_name = '$this->file_original_name'
                WHERE file_id = $this->file_id";

        if (mysqli_query($this->conn, $sql)) {
            // echo "Row updated successfully.<br>";
        } else {
            // echo "Error updating row: " . mysqli_error($this->conn) . "<br>";
        }
    }

    public function updateStatus()
    {
        $sql = "UPDATE tbl_file SET status = $this->status WHERE file_id = $this->file_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Status updated successfully.<br>";
        } else {
            // echo "Error updating status: " . mysqli_error($this->conn) . "<br>";
        }
    }

    public function updateFileOwner()
    {
        $sql = "UPDATE tbl_file SET file_owner = '$this->file_owner' WHERE file_id = $this->file_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "File owner updated successfully.<br>";
        } else {
            // echo "Error updating file owner: " . mysqli_error($this->conn) . "<br>";
        }
    }

    public function updateFileName()
    {
        $sql = "UPDATE tbl_file SET file_name = '$this->file_name' WHERE file_id = $this->file_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "File name updated successfully.<br>";
        } else {
            // echo "Error updating file name: " . mysqli_error($this->conn) . "<br>";
        }
    }

    public function updateFileOriginalName()
    {
        $sql = "UPDATE tbl_file SET file_original_name = '$this->file_original_name' WHERE file_id = $this->file_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "File name updated successfully.<br>";
        } else {
            // echo "Error updating file name: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // ************DELETE
    // NOT APPLICABLE NOW!!!!!!!!!!!!


    // ************OTHERS
    public function closeConnection()
    {
        $this->db->closeConnection();
    }
}

?>

<!-- End here -->