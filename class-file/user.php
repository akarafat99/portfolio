<?php
// Include the database connection class
include_once "connect.php";

class User
{
    // Class properties (variables) corresponding to table columns
    public $user_id;
    public $status = 'INT DEFAULT 0';  // Default data type for status
    public $profile_picture_id = 'INT DEFAULT 0';  // Default data type for profile_picture_id
    public $password = 'TEXT';  // Default data type for password
    public $user_type = 'VARCHAR(50)';  // Default data type for user_type
    public $full_name = 'VARCHAR(255)';  // Default data type for full_name
    public $email = 'VARCHAR(255)';  // Default data type for email
    public $contact_no = 'VARCHAR(20)';  // Default data type for contact_no
    public $gender = 'VARCHAR(10)';  // Default data type for gender
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
    // Create the table if it doesn't exist
    public function createTable()
    {
        // Create the base table with only the 'user_id' column
        $sql = "CREATE TABLE IF NOT EXISTS tbl_user (
            user_id INT AUTO_INCREMENT PRIMARY KEY
        )";

        if (!mysqli_query($this->conn, $sql)) {
            die("Error creating table: " . mysqli_error($this->conn));
        } else {
            echo "Table 'tbl_user' created successfully (if it did not exist).<br>";
        }

        // Call functions to add other columns
        $this->addStatusColumn();
        $this->addProfilePictureIdColumn();
        $this->addPasswordColumn();
        $this->addUserTypeColumn();
        $this->addFullNameColumn();
        $this->addEmailColumn();
        $this->addContactNoColumn();
        $this->addGenderColumn();
        $this->addCreatedColumn();
        $this->addModifiedColumn();

        $this->setDefaultAdmin();
    }

    // Function to set default admin credentials (email and password)
    public function setDefaultAdmin()
    {
        // Admin credentials
        $email = 'admin@admin';
        $password = 'Pa$$w0rd';
        $user_type = 'admin';
        $status = 1;

        // Encrypt password using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert admin into the tbl_admin table
        $sql = "INSERT INTO tbl_user (email, password, user_type, status) 
                VALUES ('$email', '$hashedPassword', '$user_type', $status)";

        if (mysqli_query($this->conn, $sql)) {
            echo "Default admin created successfully.<br>";
        } else {
            echo "Error creating default admin: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Alter table to add the 'status' column
    public function addStatusColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS status {$this->status}";
        if (!mysqli_query($this->conn, $sql)) {
            // die("Error adding column 'status': " . mysqli_error($this->conn));
        } else {
            // echo "Column 'status' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'profile_picture_id' column
    public function addProfilePictureIdColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS profile_picture_id {$this->profile_picture_id}";
        if (!mysqli_query($this->conn, $sql)) {
            // die("Error adding column 'profile_picture_id': " . mysqli_error($this->conn));
        } else {
            echo "Column 'profile_picture_id' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'password' column
    public function addPasswordColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS password {$this->password}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'password': " . mysqli_error($this->conn));
        } else {
            echo "Column 'password' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'user_type' column
    public function addUserTypeColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS user_type {$this->user_type}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'user_type': " . mysqli_error($this->conn));
        } else {
            echo "Column 'user_type' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'full_name' column
    public function addFullNameColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS full_name {$this->full_name}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'full_name': " . mysqli_error($this->conn));
        } else {
            echo "Column 'full_name' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'email' column
    public function addEmailColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS email {$this->email}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'email': " . mysqli_error($this->conn));
        } else {
            echo "Column 'email' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'contact_no' column
    public function addContactNoColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS contact_no {$this->contact_no}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'contact_no': " . mysqli_error($this->conn));
        } else {
            echo "Column 'contact_no' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'gender' column
    public function addGenderColumn()
    {
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS gender {$this->gender}";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'gender': " . mysqli_error($this->conn));
        } else {
            echo "Column 'gender' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'created' column
    public function addCreatedColumn()
    {
        // SQL query to add 'created' column with default current timestamp
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS created {$this->created} DEFAULT CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'created': " . mysqli_error($this->conn));
        } else {
            echo "Column 'created' added successfully (if it did not exist).<br>";
        }
    }

    // Alter table to add the 'modified' column
    public function addModifiedColumn()
    {
        // SQL query to add 'modified' column with auto-update current timestamp on modification
        $sql = "ALTER TABLE tbl_user ADD COLUMN IF NOT EXISTS modified {$this->modified} DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error adding column 'modified': " . mysqli_error($this->conn));
        } else {
            echo "Column 'modified' added successfully (if it did not exist).<br>";
        }
    }

    // Insert user
    public function insertUser()
    {
        // Hash the password before inserting it
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the user data
        $sql = "INSERT INTO tbl_user (status, profile_picture_id, password, user_type, full_name, email, contact_no, gender)
                VALUES ($this->status, '$this->profile_picture_id', '$hashedPassword', '$this->user_type', '$this->full_name', '$this->email', '$this->contact_no', '$this->gender')";

        if (mysqli_query($this->conn, $sql)) {
            // Get the ID of the newly inserted user
            $insertedId = mysqli_insert_id($this->conn);
            // echo "User inserted successfully with user_id: $insertedId<br>";
            return $insertedId;  // Return the user_id of the inserted record
        } else {
            // echo "Error inserting user: " . mysqli_error($this->conn) . "<br>";
            return false;
        }
    }



    // ************READ
    public function setAllValues()
    {
        $sql = "SELECT * FROM tbl_user WHERE user_id = {$this->user_id}";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $this->status = $row['status'];
            $this->profile_picture_id = $row['profile_picture_id'];
            // $this->password = $row['password'];
            $this->user_type = $row['user_type'];
            $this->full_name = $row['full_name'];
            $this->email = $row['email'];
            $this->contact_no = $row['contact_no'];
            $this->gender = $row['gender'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
        } else {
            // echo "No record found for user_id: $user_id.<br>";
        }
    }

    // Get user_id
    public function getUserId()
    {
        $sql = "SELECT user_id FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->user_id = $row['user_id'];
        }
        return $this->user_id;
    }

    // Get status
    public function getStatus()
    {
        $sql = "SELECT status FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->status = $row['status'];
        }
        return $this->status;
    }

    // Get profile_picture_id
    public function getProfilePictureId()
    {
        $sql = "SELECT profile_picture_id FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->profile_picture_id = $row['profile_picture_id'];
        }
        return $this->profile_picture_id;
    }

    // Get password
    public function getPassword()
    {
        $sql = "SELECT password FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->password = $row['password'];
        }
        return $this->password;
    }

    // Get user_type
    public function getUserType()
    {
        $sql = "SELECT user_type FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->user_type = $row['user_type'];
        }
        return $this->user_type;
    }

    // Get full_name
    public function getFullName()
    {
        $sql = "SELECT full_name FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->full_name = $row['full_name'];
        }
        return $this->full_name;
    }

    // Get email
    public function getEmail()
    {
        $sql = "SELECT email FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->email = $row['email'];
        }
        return $this->email;
    }

    // Get contact_no
    public function getContactNo()
    {
        $sql = "SELECT contact_no FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->contact_no = $row['contact_no'];
        }
        return $this->contact_no;
    }

    // Get gender
    public function getGender()
    {
        $sql = "SELECT gender FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->gender = $row['gender'];
        }
        return $this->gender;
    }

    // Get created
    public function getCreated()
    {
        $sql = "SELECT created FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->created = $row['created'];
        }
        return $this->created;
    }

    // Get modified
    public function getModified()
    {
        $sql = "SELECT modified FROM tbl_user WHERE user_id = $this->user_id";
        $result = mysqli_query($this->conn, $sql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $this->modified = $row['modified'];
        }
        return $this->modified;
    }

    // Get all user_ids based on the status of the class variable
    public function getAllUserIdsByStatus()
    {
        // Prepare the SQL query to fetch user_ids based on the class status
        $sql = "SELECT user_id FROM tbl_user WHERE status = $this->status";

        // Execute the query
        $result = mysqli_query($this->conn, $sql);

        // Check if there are results
        if ($result) {
            // Initialize an array to store the user IDs
            $user_ids = [];

            // Fetch all rows and store the user_ids in the array
            while ($row = mysqli_fetch_assoc($result)) {
                $user_ids[] = $row['user_id'];
            }

            // Return the array of user_ids
            return $user_ids;
        } else {
            // If no results or query error, return an empty array
            return [];
        }
    }

    // Get all users by status
    public function getAllUsers()
    {
        $sql = "SELECT * FROM tbl_user WHERE status = $this->status AND user_type = '$this->user_type'";
        $result = mysqli_query($this->conn, $sql);
        $users = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        return $users;
    }


    // ************UPDATE

    // Update a row based on user_id and class variables (excluding created and modified)
    public function updateUser()
    {
        // Prepare the SQL query to update the row excluding created and modified columns
        $sql = "UPDATE tbl_user SET 
                status = $this->status,
                profile_picture_id = $this->profile_picture_id,
                user_type = '$this->user_type',
                full_name = '$this->full_name',
                email = '$this->email',
                contact_no = '$this->contact_no',
                gender = '$this->gender'
            WHERE user_id = $this->user_id";

        // Execute the query
        if (mysqli_query($this->conn, $sql)) {
            // echo "User with ID $this->user_id updated successfully.<br>";
        } else {
            // echo "Error updating user: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update status for a specific user_id
    public function updateStatus()
    {
        $sql = "UPDATE tbl_user SET status = '$this->status' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Status updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating status: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update profile_picture_id for a specific user_id
    public function updateProfilePictureId()
    {
        $sql = "UPDATE tbl_user SET profile_picture_id = '$this->profile_picture_id' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Profile picture updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating profile picture: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update password for a specific user_id
    public function updatePassword()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "UPDATE tbl_user SET password = '$hashedPassword' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Password updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating password: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update user_type for a specific user_id
    public function updateUserType()
    {
        $sql = "UPDATE tbl_user SET user_type = '$this->user_type' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "User type updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating user type: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update full_name for a specific user_id
    public function updateFullName()
    {
        $sql = "UPDATE tbl_user SET full_name = '$this->full_name' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Full name updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating full name: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update email for a specific user_id
    public function updateEmail()
    {
        $sql = "UPDATE tbl_user SET email = '$this->email' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Email updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating email: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update contact_no for a specific user_id
    public function updateContactNo()
    {
        $sql = "UPDATE tbl_user SET contact_no = '$this->contact_no' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Contact number updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating contact number: " . mysqli_error($this->conn) . "<br>";
        }
    }

    // Update gender for a specific user_id
    public function updateGender()
    {
        $sql = "UPDATE tbl_user SET gender = '$this->gender' WHERE user_id = $this->user_id";
        if (mysqli_query($this->conn, $sql)) {
            // echo "Gender updated successfully for user_id: $this->user_id<br>";
        } else {
            // echo "Error updating gender: " . mysqli_error($this->conn) . "<br>";
        }
    }


    // ************DELETE
    // NOT APPLICABLE NOW!!!!!!!!!!!!


    // ************OTHERS
    public function closeConnection()
    {
        $this->db->closeConnection();
    }

    // Check if the email is already taken
    public function checkEmailExists()
    {
        // Prepare the SQL query to check if the email already exists in the database
        $sql = "SELECT * FROM tbl_user WHERE email = '$this->email'";

        // Execute the query
        $result = mysqli_query($this->conn, $sql);

        // If a row is returned, the email already exists
        if ($result && mysqli_num_rows($result) > 0) {
            $this->user_id = mysqli_fetch_assoc($result)['user_id'];
            return 1; // Email exists
        } else {
            return 0; // Email does not exist
        }
    }

    // Function to verify email and password
    public function verifyUser($inputEmail, $inputPassword)
    {
        $sql = "SELECT * FROM tbl_user WHERE email = '$inputEmail'";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($inputPassword, $row['password'])) {
                $this->user_id = $row['user_id'];
                return $row['user_id']; // Match successful
            } else {
                // return "Error: Password does not match.<br>";
                // echo "Error: Password does not match.<br>";
                return -1;
            }
        } else {
            // return "Error: No admin found with email $inputEmail.<br>";
            return -2;
        }
    }
}

?>

<!-- End here -->