<?php
include_once 'DatabaseConnector.php';

class User
{
    public $conn;
    public $db;
    public $table = null;

    public $user_id             = 0;
    public $status              = 0;
    public $email               = "";
    public $password            = "";
    public $user_type           = "client";
    public $full_name           = "";
    public $contact_no          = "";
    public $gender              = "";
    public $profile_picture_id  = 0;
    public $created_at          = "";
    public $modified_at         = "";

    /**
     * User constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_user';
    }

    /**
     * Ensure there is an active database connection.
     *
     * @return void
     */
    public function ensureConnection()
    {
        if (!$this->conn) {
            $this->db = new DatabaseConnector();
            $this->db->connect();
            $this->conn = $this->db->getConnection();
        }
    }

    /**
     * Disconnects from the database.
     *
     * @return void
     */
    public function disconnect()
    {
        if ($this->db) {
            $this->db->disconnect();
        }
        $this->conn = null;
    }

    /**
     * Creates the minimal user table with only a primary key.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            user_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Adds missing columns to the user table.
     *
     * @param int[] $indexes Optional list of 1-based column indexes to add.
     *                       If empty, all columns will be added.
     * @return void
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1  => ['name' => 'status',             'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2  => ['name' => 'email',              'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS email TEXT"],
            3  => ['name' => 'password',           'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS password TEXT"],
            4  => ['name' => 'user_type',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS user_type TEXT"],
            5  => ['name' => 'full_name',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS full_name TEXT"],
            6  => ['name' => 'contact_no',         'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS contact_no TEXT"],
            7  => ['name' => 'gender',             'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS gender TEXT"],
            8  => ['name' => 'profile_picture_id', 'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS profile_picture_id INT"],
            9  => ['name' => 'created_at',         'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            10 => ['name' => 'modified_at',        'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
        ];


        $toRun = empty($indexes)
            ? array_keys($columns)
            : array_intersect(array_keys($columns), $indexes);

        foreach ($toRun as $i) {
            $col   = $columns[$i]['name'];
            $sql   = $columns[$i]['sql'];
            $label = "#{$i} ({$col})";

            if (mysqli_query($this->conn, $sql)) {
                echo "✅ {$label} added successfully.<br>";
            } else {
                echo "ℹ️ {$label} might already exist or error: "
                    . mysqli_error($this->conn)
                    . "<br>";
            }
        }
    }

    /**
     * Escapes a value for safe SQL insertion.
     *
     * @param string $value Raw input value.
     * @return string Escaped string.
     */
    private function filter($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    /**
     * Hashes a plaintext password using bcrypt.
     *
     * @param string $password Plaintext password.
     * @return string Hashed password.
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Inserts a new user record into the database.
     *
     * @return int The newly created user_id, or 0 on failure.
     */
    public function insert()
    {
        $this->password = $this->hashPassword($this->password);
        $sql = "INSERT INTO {$this->table} (
            status, email, password, user_type, full_name, contact_no,
            gender, profile_picture_id
        ) VALUES (
            {$this->status},
            '" . $this->filter($this->email) . "',
            '" . $this->filter($this->password) . "',
            '" . $this->filter($this->user_type) . "',
            '" . $this->filter($this->full_name) . "',
            '" . $this->filter($this->contact_no) . "',
            '" . $this->filter($this->gender) . "',
            {$this->profile_picture_id}
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->user_id = mysqli_insert_id($this->conn);
            return $this->user_id;
        }
        return 0;
    }

    /**
     * Updates the user record in the database.
     *
     * @return bool True on success, false on failure.
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status = {$this->status},
            email   = '" . $this->filter($this->email) . "',
            password= '" . $this->filter($this->password) . "',
            user_type           = '" . $this->filter($this->user_type) . "',
            full_name           = '" . $this->filter($this->full_name) . "',
            contact_no          = '" . $this->filter($this->contact_no) . "',
            gender              = '" . $this->filter($this->gender) . "',
            profile_picture_id  = {$this->profile_picture_id}
          WHERE user_id = {$this->user_id}";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update status of a user.
     * 
     * @param int $user_id User ID to update.
     * @param int $status New status value default is 1.
     * @return bool True on success, false on failure.
     */
    public function updateStatus($user_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET
            status = {$status}
          WHERE user_id = {$user_id}";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Sets object properties from a database row.
     *
     * @param array $row Associative array of column => value.
     * @return void
     */
    public function setProperties($row)
    {
        foreach ($row as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Check if user email exists and validate status.
     * 
     * @param string $email User email
     * @param string $password User password
     * @param string $user_type User type (default is "user")
     * @return array Returns an array where index 0 is the status value and index 1 is the status message.
     */
    public function checkUserEmailWithStatus($email, $password, $user_type = null)
    {
        $email = mysqli_real_escape_string($this->conn, $email);

        // Query to fetch all users with the given email and user_type
        $sql = "SELECT * FROM {$this->table} WHERE email = '$email'";
        if ($user_type !== null) {
            $sql .= " AND user_type = '$user_type'";
        }
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch all rows as an associative array
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Initialize variables to track status
            $hasDeleted = false;
            $hasPendingApproval = false;
            $hasBlocked = false;
            $hasDeclined = false;

            // Iterate through each row to check statuses
            foreach ($rows as $row) {
                $status = (int)$row['status'];
                $user_id = (int)$row['user_id'];

                // Check for deleted status (-2)
                if ($status == -2) {
                    $hasDeleted = true;
                    continue;
                }

                // Check for approved status (1)
                if ($status == 1) {
                    if (password_verify($password, $row['password'])) {
                        $this->user_id = $row['user_id'];
                        $this->getByFilters($user_id);
                        return ["1", "Login successful! User ID: " . $row['user_id']];
                    } else {
                        return ["10", "Wrong password. Please try again."];
                    }
                }

                // Check for pending approval status (0)
                if ($status == 0) {
                    $hasPendingApproval = true;
                    return ["0", "Your account is pending approval."];
                }

                // Check for blocked status (2)
                if ($status == 2) {
                    $hasBlocked = true;
                    return ["2", "Your account is blocked by admin."];
                }

                // Check for declined status (-1)
                if ($status == -1) {
                    $hasDeclined = true;
                    return ["-1", "Your account registration was declined by the admin. Please register again using <b>this email<b> or other email account.", $user_id];
                }
            }
        }

        // If no account is found, return a default status
        return ["-99", "No account found with the email '$email'."];
    }

    /**
     * Check if an email is available for registration.
     * It checks if a record exists in the database based on the given email,
     * status filter, and user type filter.
     *
     * @param string|null $email The email address to check (default NULL).
     * @param array|int|null $status (Optional) Status filter as an array of numbers or a single number (default NULL).
     * @param string|array|null $user_type (Optional) User type filter as a string or array of strings (default NULL).
     * @return int Returns 1 if any matching row exists, 0 otherwise.
     */
    public function isEmailAvailable($email = null, $status = null, $user_type = null)
    {
        $sql = "SELECT user_id FROM {$this->table} WHERE 1=1";

        if ($email !== null) {
            $email = mysqli_real_escape_string($this->conn, $email);
            $sql .= " AND email = '$email'";
        }

        if ($status !== null) {
            if (is_array($status)) {
                $statusArray = array_map('intval', $status);
                $statusList = implode(',', $statusArray);
                $sql .= " AND status IN ($statusList)";
            } else {
                $status = intval($status);
                $sql .= " AND status = $status";
            }
        }

        if ($user_type !== null) {
            if (is_array($user_type)) {
                $escapedTypes = array_map(function ($ut) {
                    return "'" . mysqli_real_escape_string($this->conn, $ut) . "'";
                }, $user_type);
                $userTypeList = implode(',', $escapedTypes);
                $sql .= " AND user_type IN ($userTypeList)";
            } else {
                $user_type = mysqli_real_escape_string($this->conn, $user_type);
                $sql .= " AND user_type = '$user_type'";
            }
        }

        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_assoc($result);
            $this->user_id = $data['user_id'];
        }

        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Retrieves users matching provided filters, with optional sorting.
     *
     * @param int|int[]|null    $user_id             Filter by one or more user IDs.
     * @param int|int[]|null    $status              Filter by status.
     * @param string|string[]|null $email             Filter by email.
     * @param string|string[]|null $user_type         Filter by user type.
     * @param string|string[]|null $full_name         Filter by full name.
     * @param string|string[]|null $contact_no        Filter by contact number.
     * @param string|string[]|null $gender            Filter by gender.
     * @param int|int[]|null    $profile_picture_id  Filter by profile picture ID.
     * @param string             $sort_col           Column to sort by (default: 'created_at').
     * @param string             $sort_order         Sort direction ('ASC' or 'DESC').
     * @return array            Array of associative rows (empty if none).
     */
    public function getByFilters(
        $user_id             = null,
        $status              = null,
        $email               = null,
        $user_type           = null,
        $full_name           = null,
        $contact_no          = null,
        $gender              = null,
        $profile_picture_id  = null,
        $sort_col            = "created_at",
        $sort_order          = 'ASC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = [
            'user_id'            => $user_id,
            'status'             => $status,
            'email'              => $email,
            'user_type'          => $user_type,
            'full_name'          => $full_name,
            'contact_no'         => $contact_no,
            'gender'             => $gender,
            'profile_picture_id' => $profile_picture_id
        ];

        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    $safe = array_map(function ($v) use ($col) {
                        return in_array($col, ['user_id', 'status', 'profile_picture_id'])
                            ? intval($v)
                            : "'" . $this->filter($v) . "'";
                    }, $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    $safeVal = in_array($col, ['user_id', 'status', 'profile_picture_id'])
                        ? intval($val)
                        : "'" . $this->filter($val) . "'";
                    $sql .= " AND {$col} = {$safeVal}";
                }
            }
        }

        if ($sort_col) {
            $sql .= " ORDER BY {$sort_col} {$sort_order}";
        }

        $res = mysqli_query($this->conn, $sql);
        if (!$res || mysqli_num_rows($res) === 0) {
            return [];
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        if (count($rows) === 1) {
            $this->setProperties($rows[0]);
        }
        return $rows;
    }
}
?>
<!-- end of the file User.php -->