<?php
include_once('connect.php');  // Include the database connection file

class Research
{
    // Properties for table columns with default data types
    public $research_id; // Primary key
    public $status = 'INT DEFAULT 1';
    public $owner = 'INT DEFAULT 0';
    public $research_type = 'VARCHAR(255)';
    public $research_title = 'TEXT';
    public $research_link = 'TEXT';
    public $comment = 'TEXT';
    public $published_on = 'DATETIME';
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

    // Create the tbl_research table
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_research (
            research_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_research created successfully or already exists.<br>";
            // Add each column
            $this->addStatusColumn();
            $this->addOwnerColumn(); // Adding owner column
            $this->addResearchTypeColumn();
            $this->addResearchTitleColumn();
            $this->addResearchLinkColumn();
            $this->addCommentColumn();
            $this->addPublishedOnColumn();
            $this->addCreatedColumn();
            $this->addModifiedColumn();
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Add the owner column
    public function addOwnerColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS owner {$this->owner}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'owner': " . mysqli_error($this->conn));
        } else {
            echo "Column 'owner' added successfully.<br>";
        }
    }

    // Add the status column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS status {$this->status}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully.<br>";
        }
    }

    // Add the research_type column
    public function addResearchTypeColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS research_type {$this->research_type}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'research_type': " . mysqli_error($this->conn));
        } else {
            echo "Column 'research_type' added successfully.<br>";
        }
    }

    // Add the research_title column
    public function addResearchTitleColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS research_title {$this->research_title}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'research_title': " . mysqli_error($this->conn));
        } else {
            echo "Column 'research_title' added successfully.<br>";
        }
    }

    // Add the research_link column
    public function addResearchLinkColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS research_link {$this->research_link}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'research_link': " . mysqli_error($this->conn));
        } else {
            echo "Column 'research_link' added successfully.<br>";
        }
    }

    public function addCommentColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS comment {$this->comment}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'comment': " . mysqli_error($this->conn));
        } else {
            echo "Column 'comment' added successfully.<br>";
        }
    }

    // Add the made_on column
    public function addPublishedOnColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS published_on {$this->published_on}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'published_on': " . mysqli_error($this->conn));
        } else {
            echo "Column 'published_on' added successfully.<br>";
        }
    }

    // Add the created column
    public function addCreatedColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully.<br>";
        }
    }

    // Add the modified column
    public function addModifiedColumn()
    {
        $sql = "ALTER TABLE tbl_research ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully.<br>";
        }
    }

    // Insert a new research record with owner
    public function insertResearch()
    {
        $sql = "INSERT INTO tbl_research (status, owner, research_type, research_title, research_link, comment, published_on) 
            VALUES ($this->status, $this->owner, '$this->research_type', '$this->research_title', '$this->research_link', '$this->comment', '$this->published_on')";

        if (mysqli_query($this->conn, $sql)) {
            $last_id = mysqli_insert_id($this->conn);
            return $last_id;
        } else {
            return false;
        }
    }


    // Read a research record by ID and set class properties
    public function getResearchById()
    {
        $sql = "SELECT * FROM tbl_research WHERE research_id = $this->research_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            // Set the class properties with the retrieved data
            $this->status = $row['status'];
            $this->owner = $row['owner']; // Get owner data
            $this->research_type = $row['research_type'];
            $this->research_title = $row['research_title'];
            $this->research_link = $row['research_link'];
            $this->comment = $row['comment'];
            $this->published_on = $row['published_on'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            // echo "Research record loaded into class properties.<br>";
            return true;
        } else {
            // echo "No research record found with ID $this->research_id.<br>";
            return false;
        }
    }

    // Method to fetch all research records
    public function getAllResearch()
    {
        // SQL query to select all records from the table
        $sql = "SELECT * FROM tbl_research";
        $result = mysqli_query($this->conn, $sql);

        // Check if query was successful
        if ($result) {
            $researchRecords = []; // Initialize an empty array to store the rows

            // Fetch each row from the result and store it in the array
            while ($row = mysqli_fetch_assoc($result)) {
                $researchRecords[] = $row;
            }

            // Return the array of all research records
            return $researchRecords;
        } else {
            // echo "Error fetching research records: " . mysqli_error($this->conn) . "<br>";
            return false;
        }
    }

    // Update the research record with owner
    public function updateResearch()
    {
        $sql = "UPDATE tbl_research SET
            status = $this->status,
            owner = '$this->owner',
            research_type = '$this->research_type',
            research_title = '$this->research_title',
            research_link = '$this->research_link',
            comment = '$this->comment',
            published_on = '$this->published_on'
            WHERE research_id = $this->research_id";

        if (mysqli_query($this->conn, $sql)) {
            // echo "Research record updated successfully.<br>";
            return 1;
        } else {
            return 0;
            // echo "Error updating research record: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Close the database connection
    public function closeConnection()
    {
        $this->db->closeConnection();
    }
}
?>

<!-- END -->