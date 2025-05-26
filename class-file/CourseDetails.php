<?php
include_once 'DatabaseConnector.php';

class CourseDetails
{
    public $conn;
    public $db;
    public $table = null;

    public $details_id = 0;
    public $status = 0;
    public $course_id = 0;
    public $day_no = 0;
    public $content_details = "";
    public $resource_files = "";
    public $comment = "";
    public $created_at = "";
    public $modified_at = "";

    /**
     * CourseDetails constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_course_details';
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
     * Creates minimal course details table with only a primary key.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            details_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Adds missing columns to the course details table.
     *
     * @param int[] $indexes Optional list of column indexes (1-based) to add.
     *                       If empty, all columns are added.
     * @return void
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1 => ['name' => 'status',           'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2 => ['name' => 'course_id',        'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS course_id INT"],
            3 => ['name' => 'day_no',           'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS day_no INT"],
            4 => ['name' => 'content_details',  'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS content_details TEXT"],
            5 => ['name' => 'resource_files',   'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS resource_files TEXT"],
            6 => ['name' => 'comment',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS comment TEXT"],
            7 => ['name' => 'created_at',       'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            8 => ['name' => 'modified_at',      'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
        ];

        $toRun = empty($indexes) ? array_keys($columns) : array_intersect(array_keys($columns), $indexes);
        foreach ($toRun as $i) {
            $col = $columns[$i]['name'];
            $sql = $columns[$i]['sql'];
            $label = "#{$i} ({$col})";
            if (mysqli_query($this->conn, $sql)) {
                echo "✅ {$label} added successfully.<br>";
            } else {
                echo "ℹ️ {$label} might already exist or error: " . mysqli_error($this->conn) . "<br>";
            }
        }
    }

    /**
     * Escapes a value for safe SQL.
     *
     * @param string $value
     * @return string
     */
    private function filter($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    /**
     * Inserts a new course details record.
     *
     * @return int New details_id or 0 on failure.
     */
    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, course_id, day_no, content_details,
            resource_files, comment
        ) VALUES (
            {$this->status},
            {$this->course_id},
            {$this->day_no},
            '" . $this->filter($this->content_details) . "',
            '" . $this->filter($this->resource_files) . "',
            '" . $this->filter($this->comment) . "'
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->details_id = mysqli_insert_id($this->conn);
            return $this->details_id;
        }
        return 0;
    }

    /**
     * Updates an existing record.
     *
     * @return bool
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status = {$this->status},
            course_id = {$this->course_id},
            day_no = {$this->day_no},
            content_details = '" . $this->filter($this->content_details) . "',
            resource_files = '" . $this->filter($this->resource_files) . "',
            comment = '" . $this->filter($this->comment) . "'
          WHERE details_id = {$this->details_id}";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update status only.
     *
     * @param int $details_id
     * @param int $status
     * @return bool
     */
    public function updateStatus($details_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE details_id = {$details_id}";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Populate object from row.
     *
     * @param array $row
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
     * Fetch by filters with optional sorting.
     *
     * @param mixed ... see code
     * @return array
     */
    public function getByFilters(
        $details_id = null,
        $status = null,
        $course_id = null,
        $day_no = null,
        $sort_col = 'created_at',
        $sort_order = 'ASC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = [
            'details_id' => $details_id,
            'status'     => $status,
            'course_id'  => $course_id,
            'day_no'     => $day_no
        ];
        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    $safe = array_map(function($v) use ($col) {
                        return in_array($col, ['details_id','status','course_id','day_no'])
                            ? intval($v)
                            : "'" . $this->filter($v) . "'";
                    }, $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    $safeVal = in_array($col, ['details_id','status','course_id','day_no'])
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

<!-- end of class-file/course-details.php -->