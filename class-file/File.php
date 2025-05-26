<?php
include_once 'DatabaseConnector.php';

class FileManager
{
    public $file_id = 0;
    public $status = 1; // Default status: Active
    public $file_owner_id = 0;
    public $file_original_name = '0.jpg';
    public $file_new_name = '0.jpg';
    public $created;
    public $modified;
    private $conn;

    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct()
    {
        $this->ensureConnection();
    }

    /**
     * Ensures that a database connection is established.
     */
    public function ensureConnection()
    {
        if (!$this->conn) { // Check if connection is not set
            $db = new DatabaseConnector(); // Create DB Connection
            $db->connect();
            $this->conn = $db->getConnection();
        } else {
            return 0;
        }
    }

    /**
     * Create minimal tbl_file with only the file_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        // Use the connection from the DatabaseConnector wrapper.
        $sql = "CREATE TABLE IF NOT EXISTS tbl_file (
                file_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_file' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_file': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_file to add additional columns.
     *
     * Each query is defined as a map entry where the key is a number and the value is an array:
     * [column name, SQL query].
     *
     * @param array|null $selectedNums Optional array of numbers. If provided, only the queries with these keys will run.
     * @return void
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $this->ensureConnection();
        $table = "tbl_file";

        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1 => ['status',             "ALTER TABLE $table ADD COLUMN status INT DEFAULT 1"],
            2 => ['file_owner_id',      "ALTER TABLE $table ADD COLUMN file_owner_id INT NOT NULL"],
            3 => ['file_original_name', "ALTER TABLE $table ADD COLUMN file_original_name TEXT NOT NULL"],
            4 => ['file_new_name',      "ALTER TABLE $table ADD COLUMN file_new_name TEXT NOT NULL"],
            5 => ['created',            "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            6 => ['modified',           "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
        ];

        // If a subset of queries is provided, filter the map.
        if ($selectedNums !== null && is_array($selectedNums)) {
            $filteredQueries = [];
            foreach ($selectedNums as $num) {
                if (isset($alterQueries[$num])) {
                    $filteredQueries[$num] = $alterQueries[$num];
                }
            }
            $alterQueries = $filteredQueries;
        }

        // Execute each query in the map.
        foreach ($alterQueries as $num => $queryInfo) {
            list($colName, $sql) = $queryInfo;
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                echo "Column '{$colName}' added successfully to table '{$table}' (Key: {$num}).<br>";
            } else {
                echo "Error adding column '{$colName}' to table '{$table}' (Key: {$num}): " . mysqli_error($this->conn) . "<br>";
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
     * Insert a new file record.
     * @return int|false Returns inserted file_id or false on failure
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_file (status, file_owner_id, file_original_name, file_new_name)
                VALUES ($this->status, $this->file_owner_id, '{$this->filter($this->file_original_name)}', '{$this->filter($this->file_new_name)}')";

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $this->file_id = mysqli_insert_id($this->conn);
            return $this->file_id;
        }

        return false;
    }

    /**
     * Update file details based on file_id.
     * Uses class properties.
     * @return bool Returns true on success, false on failure
     */
    public function update()
    {
        $sql = "UPDATE tbl_file SET 
                    status = $this->status, 
                    file_original_name = '{$this->filter($this->file_original_name)}', 
                    file_new_name = '{$this->filter($this->file_new_name)}'
                WHERE file_id = $this->file_id";

        return mysqli_query($this->conn, $sql);
    }


    /**
     * Check if a file exists by file_id and status.
     * @param int $file_id File ID
     * @param int $status Status filter
     * @return int Returns 1 if exists, 0 otherwise
     */
    public function isFileAvailable($file_id, $status)
    {
        $sql = "SELECT file_id FROM tbl_file WHERE file_id = $file_id AND status = $status LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Generate a secure 12-character random string consisting of alphabets and digits.
     * 
     * This method uses PHP's built-in `random_bytes()` to generate cryptographically secure
     * random bytes and then encodes them using `base64_encode()`. 
     * Unwanted characters (`+`, `/`, `=`) from base64 encoding are removed to ensure the 
     * final string consists of only letters and numbers.
     *
     * @param int $length The length of the random string (default is 12).
     * @return string The generated random string.
     */
    function generateRandomString($length = 12)
    {
        // Generate 9 random bytes (since base64 encoding expands the size)
        $randomBytes = random_bytes(9);

        // Encode bytes to base64 (produces a longer string containing letters, digits, '+', '/', '=')
        $base64String = base64_encode($randomBytes);

        // Remove unwanted characters ('+', '/', '=' from base64 encoding)
        $filteredString = str_replace(['+', '/', '='], '', $base64String);

        // Ensure the final string is exactly the requested length
        return substr($filteredString, 0, $length);
    }

    /**
     * Process the uploaded file:
     * - Saves the original file name.
     * - Extracts the file extension.
     * - Generates a new random file name with the original extension.
     * - Moves the file to the "../uploads1" directory.
     *
     * @param array $file An element from the $_FILES array.
     * @return string Returns the new file name on success.
     * @throws Exception if the file is not valid or the move fails.
     */
    public function doOp($file)
    {
        $this->insert();

        // Check if the file was uploaded without errors
        if (isset($file) && $file['error'] === 0) {
            // Save the original file name with extension
            $originalFileName = $file['name'];

            // Extract the file extension (e.g., jpg, png)
            $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

            $this->file_original_name = $originalFileName;
            // Generate a new random string and append the original extension
            $newFileName = $this->generateRandomString() . $this->file_id . '.' . $extension;

            $this->file_new_name = $newFileName;

            echo $this->file_new_name . "<br>";

            // Set the destination path in the "../uploads1" directory
            // $destination = 'uploads1/' . $newFileName;
            $destination = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . '/uploads1/' . $newFileName;
            // echo $_SERVER['DOCUMENT_ROOT'] . "<br>";
            // echo $destination . "<br>";


            // Move the uploaded file to the destination directory
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Return the new file name (or you can return the full path)
                $this->update();
                return 1;
            } else {
                // Throw an exception if the file couldn't be moved
                // throw new Exception("Failed to move uploaded file.");
                return -1;
            }
        } else {
            // Throw an exception if the file is invalid or an error occurred during upload
            // throw new Exception("Invalid file or file upload error.");
            return 0;
        }
    }

    /**
     * Retrieve files by filters.
     *
     * @param  int|int[]|null  $file_id         Single file ID or array of IDs to filter by.
     * @param  int|int[]|null  $status          Single status or array of statuses to filter by.
     * @param  int|int[]|null  $file_owner_id   Single owner ID or array of owner IDs to filter by.
     * @return array                         Array of associative arrays for each matching record.
     */
    public function getByFilters($file_id = null, $status = null, $file_owner_id = null): array
    {
        $sql     = "SELECT * FROM tbl_file WHERE 1";
        $filters = [
            'file_id'       => $file_id,
            'status'        => $status,
            'file_owner_id' => $file_owner_id,
        ];

        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    // build IN list for array values
                    $safe = array_map(fn($v) => intval($v), $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    // single scalar value
                    $sql .= " AND {$col} = " . intval($val);
                }
            }
        }

        $res = mysqli_query($this->conn, $sql);
        if (!$res || mysqli_num_rows($res) === 0) {
            return [];
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }

        // If exactly one row, populate this object's properties
        if (count($rows) === 1) {
            $this->setProperties($rows[0]);
        }

        return $rows;
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
}
?>

<!-- end -->