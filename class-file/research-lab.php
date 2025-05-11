<?php
include_once('connect.php'); // Include the database connection file

class ResearchLab
{
    // Properties for table columns with default data types
    public $lab_id; // Primary key
    public $status = 'INT DEFAULT 1';
    public $lab_title = 'TEXT';
    public $lab_about = 'TEXT';
    public $lab_outcomes = 'TEXT';
    public $lab_head_name = 'TEXT';
    public $lab_head_details = 'TEXT';
    public $lab_members_name = 'TEXT';
    public $lab_members_details = 'TEXT';
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

    // Create the tbl_research_lab table
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_research_lab (
            lab_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_research_lab created successfully or already exists.<br>";
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }

        $this->addStatusColumn();
        $this->addLabTitleColumn();
        $this->addLabAboutColumn();
        $this->addLabOutcomesColumn();
        $this->addLabHeadNameColumn();
        $this->addLabHeadDetailsColumn();
        $this->addLabMembersNameColumn();
        $this->addLabMembersDetailsColumn();
        $this->addCreatedColumn();
        $this->addModifiedColumn();
    }

    // Add status column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS status {$this->status}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully.<br>";
        }
    }

    // Add lab_title column
    public function addLabTitleColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_title {$this->lab_title}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_title': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_title' added successfully.<br>";
        }
    }

    // Add lab_about column
    public function addLabAboutColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_about {$this->lab_about}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_about': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_about' added successfully.<br>";
        }
    }

    // Add lab_outcomes column
    public function addLabOutcomesColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_outcomes {$this->lab_outcomes}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_outcomes': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_outcomes' added successfully.<br>";
        }
    }

    // Add lab_head_name column
    public function addLabHeadNameColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_head_name {$this->lab_head_name}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_head_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_head_name' added successfully.<br>";
        }
    }

    // Add lab_head_details column
    public function addLabHeadDetailsColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_head_details {$this->lab_head_details}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_head_details': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_head_details' added successfully.<br>";
        }
    }

    // Add lab_members_name column
    public function addLabMembersNameColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_members_name {$this->lab_members_name}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_members_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_members_name' added successfully.<br>";
        }
    }

    // Add lab_members_details column
    public function addLabMembersDetailsColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS lab_members_details {$this->lab_members_details}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'lab_members_details': " . mysqli_error($this->conn));
        } else {
            echo "Column 'lab_members_details' added successfully.<br>";
        }
    }

    // Add created column
    public function addCreatedColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully.<br>";
        }
    }

    // Add modified column
    public function addModifiedColumn()
    {
        $sql = "ALTER TABLE tbl_research_lab ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully.<br>";
        }
    }

    // Insert a new research lab record
    public function insertResearchLab()
    {
        $sql = "INSERT INTO tbl_research_lab (status, lab_title, lab_about, lab_outcomes, lab_head_name, lab_head_details, lab_members_name, lab_members_details) 
                VALUES ($this->status, '$this->lab_title', '$this->lab_about', '$this->lab_outcomes', '$this->lab_head_name', '$this->lab_head_details', '$this->lab_members_name', '$this->lab_members_details')";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        } else {
            return false;
        }
    }

    // Read a research lab record by ID and set class properties
    public function getResearchLabById()
    {
        $sql = "SELECT * FROM tbl_research_lab WHERE lab_id = $this->lab_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->status = $row['status'];
            $this->lab_title = $row['lab_title'];
            $this->lab_about = $row['lab_about'];
            $this->lab_outcomes = $row['lab_outcomes'];
            $this->lab_head_name = $row['lab_head_name'];
            $this->lab_head_details = $row['lab_head_details'];
            $this->lab_members_name = $row['lab_members_name'];
            $this->lab_members_details = $row['lab_members_details'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return 1;
        } else {
            return 0;
        }
    }

    // Get all research labs
    public function getAllResearchLabs()
    {
        $sql = "SELECT * FROM tbl_research_lab WHERE status = $this->status";
        $result = mysqli_query($this->conn, $sql);

        $labs = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $labs[] = $row; // Add each row to the labs array
            }
        } else {
            echo "Error fetching research labs: " . mysqli_error($this->conn);
        }

        return $labs;
    }


    // Update research lab details
    public function updateResearchLab()
    {
        $sql = "UPDATE tbl_research_lab SET
        status = $this->status,
            lab_title = '$this->lab_title',
            lab_about = '$this->lab_about',
            lab_outcomes = '$this->lab_outcomes',
            lab_head_name = '$this->lab_head_name',
            lab_head_details = '$this->lab_head_details',
            lab_members_name = '$this->lab_members_name',
            lab_members_details = '$this->lab_members_details'
            WHERE lab_id = $this->lab_id";

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

    // Other methods (insert, update, fetch by ID, etc.) can be added as needed.
}
?>

<!-- END -->