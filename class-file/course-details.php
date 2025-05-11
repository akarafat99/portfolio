<?php
include_once('connect.php'); // Include the database connection file

class CourseDetails
{
    // Properties for table columns with default data types
    public $details_id; // Primary key
    public $status = 'INT DEFAULT 1';
    public $course_id = 'INT DEFAULT 0';
    public $day_no = 'INT DEFAULT 0';
    public $content_details = 'TEXT';
    public $resource_files = 'TEXT';
    public $comment = 'TEXT';
    public $created = 'DATETIME';
    public $modified = 'DATETIME';

    private $conn; // Database connection
    private $db;

    public function __construct()
    {
        $this->db = new Connect();
        $this->conn = $this->db->conn;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_course_details (
            details_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (mysqli_query($this->conn, $sql)) {
            echo "Table tbl_course_details created successfully or already exists.<br>";
            $this->addStatusColumn(); // Add the status column
            $this->addCourseOfColumn();
            $this->addDayNoColumn();
            $this->addContentDetailsColumn();
            $this->addResourceFilesColumn();
            $this->addCommentColumn();
            $this->addCreatedColumn();
            $this->addModifiedColumn();
        } else {
            echo "Error creating table: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Add the status column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS status INT DEFAULT 1";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            echo "Column 'status' added successfully.<br>";
        }
    }

    // Add the course_id column
    public function addCourseOfColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS course_id INT DEFAULT 0";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'course_id': " . mysqli_error($this->conn));
        } else {
            echo "Column 'course_id' added successfully.<br>";
        }
    }

    // Add the day_no column
    public function addDayNoColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS day_no {$this->day_no}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'day_no': " . mysqli_error($this->conn));
        } else {
            echo "Column 'day_no' added successfully.<br>";
        }
    }

    // Add the content_details column
    public function addContentDetailsColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS content_details {$this->content_details}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'content_details': " . mysqli_error($this->conn));
        } else {
            echo "Column 'content_details' added successfully.<br>";
        }
    }

    // Add the resource_files column
    public function addResourceFilesColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS resource_files {$this->resource_files}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'resource_files': " . mysqli_error($this->conn));
        } else {
            echo "Column 'resource_files' added successfully.<br>";
        }
    }

    // Add the comment column
    public function addCommentColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS comment {$this->comment}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'comment': " . mysqli_error($this->conn));
        } else {
            echo "Column 'comment' added successfully.<br>";
        }
    }

    // Add the created column
    public function addCreatedColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully.<br>";
        }
    }

    // Add the modified column
    public function addModifiedColumn()
    {
        $sql = "ALTER TABLE tbl_course_details ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully.<br>";
        }
    }

    // Insert a new course detail
    public function insertCourseDetails()
    {
        $sql = "INSERT INTO tbl_course_details (course_id, status, content_details, resource_files, comment) 
            VALUES ($this->course_id, $this->status, '$this->content_details', '$this->resource_files', '$this->comment')";

        if (mysqli_query($this->conn, $sql)) {
            $last_id = mysqli_insert_id($this->conn);
            return $last_id;
        } else {
            return false;
        }
    }

    // Fetch a single course detail by ID
    public function getCourseDetailsByDetailsId()
    {
        $sql = "SELECT * FROM tbl_course_details WHERE details_id = $this->details_id";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $this->course_id = $row['course_id'];
            $this->status = $row['status'];
            $this->day_no = $row['day_no'];
            $this->content_details = $row['content_details'];
            $this->resource_files = $row['resource_files'];
            $this->comment = $row['comment'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return true;
        } else {
            return false;
        }
    }

    // Fetch course details by Course ID (using class property)
    public function getCourseDetailsByCourseId()
    {
        // Sanitize the course_id property to prevent SQL injection
        $this->course_id = mysqli_real_escape_string($this->conn, $this->course_id);

        // SQL query to fetch all rows where course_id matches
        $sql = "SELECT * FROM tbl_course_details WHERE course_id = '$this->course_id'";

        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $courseDetails = []; // Array to store fetched rows

            while ($row = mysqli_fetch_assoc($result)) {
                $courseDetails[] = [
                    'details_id' => $row['details_id'],
                    'course_id' => $row['course_id'],
                    'status' => $row['status'],
                    'day_no' => $row['day_no'],
                    'content_details' => $row['content_details'],
                    'resource_files' => $row['resource_files'],
                    'comment' => $row['comment'],
                    'created' => $row['created'],
                    'modified' => $row['modified']
                ];
            }

            return $courseDetails; // Return the fetched data as an array
        } else {
            return []; // Return an empty array if no matching records are found
        }
    }

    // Update a course detail
    public function updateCourseDetails()
    {
        $sql = "UPDATE tbl_course_details SET
            course_id = '$this->course_id',
            status = $this->status,
            day_no = $this->day_no,
            content_details = '$this->content_details',
            resource_files = '$this->resource_files',
            comment = '$this->comment'
            WHERE details_id = $this->details_id";

        if (mysqli_query($this->conn, $sql)) {
            // echo "Course detail updated successfully.<br>";
            return 1;
        } else {
            // echo "Error updating course detail: " . mysqli_error($this->conn) . "<br>";
            return 0;
        }
    }


    // Close the database connection
    public function closeConnection()
    {
        $this->db->closeConnection();
    }
}
