<?php
include_once('connect.php'); // Include the database connection file

class Project
{
    // Properties for table columns with default data types
    public $project_id; // Primary key
    public $status = 'INT DEFAULT 1';
    public $title = 'TEXT';
    public $description = 'TEXT';
    public $type = 'TEXT';
    public $github_link = 'TEXT';
    public $live_link = 'TEXT';
    public $created = 'DATETIME';
    public $modified = 'DATETIME';

    private $conn; // Database connection
    private $db;

    // Constructor to initialize the database connection
    public function __construct()
    {
        $this->db = new Connect();
        $this->conn = $this->db->conn;
    }

    // Create the tbl_project table
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_project (
            project_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_project created successfully or already exists.<br>";
            $this->addStatusColumn();
            $this->addTitleColumn();
            $this->addDescriptionColumn();
            $this->addTypeColumn();
            $this->addGithubLinkColumn();
            $this->addLiveLinkColumn();
            $this->addCreatedColumn();
            $this->addModifiedColumn();
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Add the status column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS status {$this->status}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully.<br>";
        }
    }

    // Add the title column
    public function addTitleColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS title {$this->title}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'title': " . mysqli_error($this->conn));
        } else {
            echo "Column 'title' added successfully.<br>";
        }
    }

    // Add the description column
    public function addDescriptionColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS description {$this->description}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'description': " . mysqli_error($this->conn));
        } else {
            echo "Column 'description' added successfully.<br>";
        }
    }

    // Add the type column
    public function addTypeColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS type {$this->type}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'type': " . mysqli_error($this->conn));
        } else {
            echo "Column 'type' added successfully.<br>";
        }
    }

    // Add the github_link column
    public function addGithubLinkColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS github_link {$this->github_link}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'github_link': " . mysqli_error($this->conn));
        } else {
            echo "Column 'github_link' added successfully.<br>";
        }
    }

    // Add the live_link column
    public function addLiveLinkColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS live_link {$this->live_link}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'live_link': " . mysqli_error($this->conn));
        } else {
            echo "Column 'live_link' added successfully.<br>";
        }
    }

    // Add the created column
    public function addCreatedColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully.<br>";
        }
    }

    // Add the modified column
    public function addModifiedColumn()
    {
        $sql = "ALTER TABLE tbl_project ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully.<br>";
        }
    }

    // Insert a new project record
    public function insertProject()
    {
        $sql = "INSERT INTO tbl_project (status, title, description, type, github_link, live_link) 
            VALUES ($this->status, '$this->title', '$this->description', '$this->type', '$this->github_link', '$this->live_link')";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        } else {
            return false;
        }
    }

    // Read a project record by ID and set class properties
    public function getProjectById()
    {
        $sql = "SELECT * FROM tbl_project WHERE project_id = $this->project_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->status = $row['status'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->type = $row['type'];
            $this->github_link = $row['github_link'];
            $this->live_link = $row['live_link'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return true;
        } else {
            return false;
        }
    }

    // Update project details
    public function updateProject()
    {
        $sql = "UPDATE tbl_project SET
             status = $this->status,
             title = '$this->title',
             description = '$this->description',
             type = '$this->type',
             github_link = '$this->github_link',
             live_link = '$this->live_link'
             WHERE project_id = $this->project_id";

        if (mysqli_query($this->conn, $sql)) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }

    // Close the database connection
    public function closeConnection()
    {
        $this->db->closeConnection();
    }

    // Others
    // Method to get all projects where 
    public function getAllProjectsByStatus()
    {
        $sql = "SELECT * FROM tbl_project WHERE status = $this->status";
        $result = mysqli_query($this->conn, $sql);

        $projects = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $projects[] = $row;
            }
        } else {
            // echo "Error fetching projects: " . mysqli_error($this->conn);
        }

        return $projects; // Returns an array of projects
    }
}
?>

<!-- END -->