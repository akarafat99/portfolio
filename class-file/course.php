<?php
include_once('connect.php');  // Include the database connection file

class Course
{
    // Properties for table columns with default data types
    public $course_id; // Primary key
    public $status = 'INT DEFAULT 1';
    public $program_name = 'VARCHAR(100)';
    public $course_name = 'VARCHAR(255)';
    public $course_code = 'VARCHAR(50)';
    public $course_details = 'TEXT';
    public $course_objectives = 'TEXT';
    public $session = 'TEXT';
    public $department = 'TEXT';
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

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_course (
        course_id INT AUTO_INCREMENT PRIMARY KEY
    )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_course created successfully or already exists.<br>";
            // Add each column
            $this->addStatusColumn();
            $this->addCourseNameColumn();
            $this->addCourseCodeColumn();
            $this->addCourseDetailsColumn();
            $this->addCourseObjectivesColumn();
            $this->addProgramNameColumn();
            $this->addSessionColumn();
            $this->addDepartmentColumn();
            $this->addCreatedColumn();
            $this->addModifiedColumn();
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }
    }


    // Add the status column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS status {$this->status}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully.<br>";
        }
    }

    // Add the program_name column
    public function addProgramNameColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS program_name {$this->program_name}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'program_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'program_name' added successfully.<br>";
        }
    }

    // Add the course_name column
    public function addCourseNameColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS course_name {$this->course_name}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'course_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'course_name' added successfully.<br>";
        }
    }

    // Add the course_code column
    public function addCourseCodeColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS course_code {$this->course_code}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'course_code': " . mysqli_error($this->conn));
        } else {
            echo "Column 'course_code' added successfully.<br>";
        }
    }

    // Add the session column
    public function addSessionColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS session {$this->session}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'session': " . mysqli_error($this->conn));
        } else {
            echo "Column 'session' added successfully.<br>";
        }
    }

    // Add the department column
    public function addDepartmentColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS department {$this->department}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'department': " . mysqli_error($this->conn));
        } else {
            echo "Column 'department' added successfully.<br>";
        }
    }


    // Add the course_details column
    public function addCourseDetailsColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS course_details {$this->course_details}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'course_details': " . mysqli_error($this->conn));
        } else {
            echo "Column 'course_details' added successfully.<br>";
        }
    }

    // Add the course_objectives column
    public function addCourseObjectivesColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS course_objectives {$this->course_objectives}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'course_objectives': " . mysqli_error($this->conn));
        } else {
            echo "Column 'course_objectives' added successfully.<br>";
        }
    }

    // Add the created column
    public function addCreatedColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully.<br>";
        }
    }

    // Add the modified column
    public function addModifiedColumn()
    {
        $sql = "ALTER TABLE tbl_course ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully.<br>";
        }
    }

    // Insert a new course
    public function insertCourse()
    {
        $sql = "INSERT INTO tbl_course (program_name, course_name, course_code, course_details, course_objectives, session, department) 
        VALUES ('$this->program_name', '$this->course_name', '$this->course_code', '$this->course_details', '$this->course_objectives', '$this->session', '$this->department')";

        if (mysqli_query($this->conn, $sql)) {
            $last_id = mysqli_insert_id($this->conn);
            return $last_id;
        } else {
            return false;
        }
    }



    // Read a course by ID and set class properties
    public function getCourseById()
    {
        $sql = "SELECT * FROM tbl_course WHERE course_id = $this->course_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->program_name = $row['program_name'];
            $this->course_name = $row['course_name'];
            $this->course_code = $row['course_code'];
            $this->course_details = $row['course_details'];
            $this->course_objectives = $row['course_objectives'];
            $this->session = $row['session'];
            $this->department = $row['department'];
            $this->status = $row['status'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return true;
        } else {
            return false;
        }
    }

    // Function to fetch all rows from the table
    public function getAllCourse()
    {
        $sql = "SELECT * FROM tbl_course";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            // echo "Error fetching data: " . mysqli_error($this->conn) . "<br>";
            return [];
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }


    // Update course details including the 'status' column
    public function updateCourse()
    {
        $sql = "UPDATE tbl_course SET
        status = $this->status,
        program_name = '$this->program_name',
        course_name = '$this->course_name',
        course_code = '$this->course_code',
        course_details = '$this->course_details',
        course_objectives = '$this->course_objectives',
        session = '$this->session',
        department = '$this->department'
        WHERE course_id = $this->course_id";

        if (mysqli_query($this->conn, $sql)) {
            return 1;
            // echo "Course updated successfully.<br>";
        } else {
            return 0;
            // echo "Error updating course: " . mysqli_error($this->conn) . "<br>";
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