<?php
include_once 'DatabaseConnector.php';

class Department
{
    public $conn;
    public $db;
    public $table = null;

    public $department_id = 0;
    public $status = 0;
    public $department_name = "";
    public $created_at = "";
    public $modified_at = "";

    /**
     * Department constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_department';
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
     * Creates minimal department table with only a primary key.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            department_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Adds missing columns to the department table.
     *
     * @param int[] $indexes Optional list of 1-based column indexes to add.
     *                       If empty, all columns will be added.
     * @return void
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1 => ['name' => 'status',          'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2 => ['name' => 'department_name','sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS department_name TEXT"],
            3 => ['name' => 'created_at',      'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            4 => ['name' => 'modified_at',     'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
        ];

        $toRun = empty($indexes) ? array_keys($columns) : array_intersect(array_keys($columns), $indexes);
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
     * Inserts a new department record into the database.
     *
     * @return int The newly created department_id, or 0 on failure.
     */
    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, department_name
        ) VALUES (
            {$this->status},
            '" . $this->filter($this->department_name) . "'
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->department_id = mysqli_insert_id($this->conn);
            return $this->department_id;
        }
        return 0;
    }

    /**
     * Updates the department record in the database.
     *
     * @return bool True on success, false on failure.
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status = {$this->status},
            department_name = '" . $this->filter($this->department_name) . "'
          WHERE department_id = {$this->department_id}";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update status of a department.
     *
     * @param int $department_id Department ID to update.
     * @param int $status New status value (default is 1).
     * @return bool True on success, false on failure.
     */
    public function updateStatus($department_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE department_id = {$department_id}";
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
     * Retrieves departments matching provided filters, with optional sorting.
     *
     * @param int|int[]|null    $department_id     Filter by one or more department IDs.
     * @param int|int[]|null    $status            Filter by status.
     * @param string|string[]|null $department_name Filter by department name.
     * @param string             $sort_col         Column to sort by (default: 'created_at').
     * @param string             $sort_order       Sort direction ('ASC' or 'DESC').
     * @return array            Array of associative rows (empty if none).
     */
    public function getByFilters(
        $department_id = null,
        $status = null,
        $department_name = null,
        $sort_col = 'created_at',
        $sort_order = 'ASC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = [
            'department_id'   => $department_id,
            'status'          => $status,
            'department_name' => $department_name
        ];

        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    $safe = array_map(function($v) use ($col) {
                        return in_array($col, ['department_id','status'])
                            ? intval($v)
                            : "'" . $this->filter($v) . "'";
                    }, $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    $safeVal = in_array($col, ['department_id','status'])
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

<!-- end -->