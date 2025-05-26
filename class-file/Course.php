<?php
include_once 'DatabaseConnector.php';

class Course
{
    public $conn;
    public $db;
    public $table = null;

    public $course_id = 0;
    public $status = 0;
    public $session = "";
    public $department = "";
    public $program_name = "";
    public $course_name = "";
    public $course_code = "";
    public $course_details = "";
    public $course_objectives = "";
    public $created_at = "";
    public $modified_at = "";

    /**
     * Course constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_course';
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
     * Creates the minimal course table with only a primary key.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            course_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Adds missing columns to the course table.
     *
     * @param int[] $indexes Optional list of 1-based column indexes to add.
     *                       If empty, all columns will be added.
     * @return void
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1  => ['name' => 'status',              'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2  => ['name' => 'session',             'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS session TEXT"],
            3  => ['name' => 'department',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS department TEXT"],
            4  => ['name' => 'program_name',        'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS program_name TEXT"],
            5  => ['name' => 'course_name',         'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS course_name TEXT"],
            6  => ['name' => 'course_code',         'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS course_code TEXT"],
            7  => ['name' => 'course_details',      'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS course_details TEXT"],
            8  => ['name' => 'course_objectives',   'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS course_objectives TEXT"],
            9  => ['name' => 'created_at',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            10 => ['name' => 'modified_at',         'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
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
                echo "ℹ️ {$label} might already exist or error: " . mysqli_error($this->conn) . "<br>";
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
     * Inserts a new course record into the database.
     *
     * @return int The newly created course_id, or 0 on failure.
     */
    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, session, department, program_name,
            course_name, course_code, course_details, course_objectives
        ) VALUES (
            {$this->status},
            '" . $this->filter($this->session) . "',
            '" . $this->filter($this->department) . "',
            '" . $this->filter($this->program_name) . "',
            '" . $this->filter($this->course_name) . "',
            '" . $this->filter($this->course_code) . "',
            '" . $this->filter($this->course_details) . "',
            '" . $this->filter($this->course_objectives) . "'
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->course_id = mysqli_insert_id($this->conn);
            return $this->course_id;
        }
        return 0;
    }

    /**
     * Updates the course record in the database.
     *
     * @return bool True on success, false on failure.
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status = {$this->status},
            session = '" . $this->filter($this->session) . "',
            department = '" . $this->filter($this->department) . "',
            program_name = '" . $this->filter($this->program_name) . "',
            course_name = '" . $this->filter($this->course_name) . "',
            course_code = '" . $this->filter($this->course_code) . "',
            course_details = '" . $this->filter($this->course_details) . "',
            course_objectives = '" . $this->filter($this->course_objectives) . "'
          WHERE course_id = {$this->course_id}";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update status of a course.
     *
     * @param int $course_id Course ID to update.
     * @param int $status New status value (default is 1).
     * @return bool True on success, false on failure.
     */
    public function updateStatus($course_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE course_id = {$course_id}";
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
     * Retrieves courses matching provided filters, with optional sorting.
     *
     * @param int|int[]|null    $course_id           Filter by one or more course IDs.
     * @param int|int[]|null    $status              Filter by status.
     * @param string|string[]|null $session           Filter by session.
     * @param string|string[]|null $department        Filter by department.
     * @param string|string[]|null $program_name      Filter by program name.
     * @param string|string[]|null $course_name       Filter by course name.
     * @param string|string[]|null $course_code       Filter by course code.
     * @param string             $sort_col           Column to sort by (default: 'created_at').
     * @param string             $sort_order         Sort direction ('ASC' or 'DESC').
     * @return array            Array of associative rows (empty if none).
     */
    public function getByFilters(
        $course_id = null,
        $status = null,
        $session = null,
        $department = null,
        $program_name = null,
        $course_name = null,
        $course_code = null,
        $sort_col = 'created_at',
        $sort_order = 'ASC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = [
            'course_id' => $course_id,
            'status' => $status,
            'session' => $session,
            'department' => $department,
            'program_name' => $program_name,
            'course_name' => $course_name,
            'course_code' => $course_code
        ];
        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    $safe = array_map(function($v) use ($col) {
                        return in_array($col, ['course_id','status']) ? intval($v) : '" . $this->filter($v) . "';
                    }, $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    $safeVal = in_array($col, ['course_id','status']) ? intval($val) : '" . $this->filter($val) . "';
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

<!-- end -->