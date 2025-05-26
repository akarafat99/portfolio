<?php
include_once 'DatabaseConnector.php';

class Project
{
    public $conn;
    public $db;
    public $table = null;

    public $project_id     = 0;
    public $status         = 1;
    public $title          = "";
    public $description    = "";
    public $project_type   = "";
    public $github_link    = "";
    public $live_link      = "";
    public $files          = "";
    public $created_at     = "";
    public $modified_at    = "";

    /**
     * Project constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_project';
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
     * Creates minimal tbl_project with only primary key if not exists.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            project_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Adds missing columns to the project table.
     *
     * @param int[] $indexes Optional list of 1-based column indexes to add.
     *                       If empty, all columns will be added.
     * @return void
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1 => ['name' => 'status',       'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2 => ['name' => 'title',        'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS title TEXT"],
            3 => ['name' => 'description',  'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS description TEXT"],
            4 => ['name' => 'project_type', 'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS project_type TEXT"],
            5 => ['name' => 'github_link',  'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS github_link TEXT"],
            6 => ['name' => 'live_link',    'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS live_link TEXT"],
            7 => ['name' => 'files',        'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS files TEXT"],
            8 => ['name' => 'created_at',   'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            9 => ['name' => 'modified_at',  'sql' => "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
        ];

        $toRun = empty($indexes)
            ? array_keys($columns)
            : array_intersect(array_keys($columns), $indexes);

        foreach ($toRun as $i) {
            $label = "#{$i} ({$columns[$i]['name']})";
            if (mysqli_query($this->conn, $columns[$i]['sql'])) {
                echo "✅ {$label} added.<br>";
            } else {
                echo "ℹ️ {$label} exists or error: " . mysqli_error($this->conn) . "<br>";
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
     * Inserts a new project record.
     *
     * @return int New project_id or 0 on failure.
     */
    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, title, description, project_type, github_link, live_link, files
        ) VALUES (
            {$this->status},
            '" . $this->filter($this->title) . "',
            '" . $this->filter($this->description) . "',
            '" . $this->filter($this->project_type) . "',
            '" . $this->filter($this->github_link) . "',
            '" . $this->filter($this->live_link) . "',
            '" . $this->filter($this->files) . "'
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->project_id = mysqli_insert_id($this->conn);
            return $this->project_id;
        }
        return 0;
    }

    /**
     * Updates an existing project record.
     *
     * @return bool True on success, false on failure.
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status       = {$this->status},
            title        = '" . $this->filter($this->title) . "',
            description  = '" . $this->filter($this->description) . "',
            project_type = '" . $this->filter($this->project_type) . "',
            github_link  = '" . $this->filter($this->github_link) . "',
            live_link    = '" . $this->filter($this->live_link) . "',
            files        = '" . $this->filter($this->files) . "'
          WHERE project_id = {$this->project_id}";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update status of a project.
     *
     * @param int $project_id Project ID.
     * @param int $status New status (default 1).
     * @return bool
     */
    public function updateStatus($project_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE project_id = {$project_id}";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Sets object properties from a database row.
     *
     * @param array $row Associative column => value.
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
     * Retrieves projects matching filters, with optional sorting.
     *
     * @param int|int[]|null    $project_id   Filter by ID(s).
     * @param int|int[]|null    $status       Filter by status.
     * @param string|string[]|null $title      Filter by title.
     * @param string|string[]|null $project_type Filter by project type.
     * @param string|string[]|null $files      Filter by comma-separated files string.
     * @param string             $sort_col     Column to sort by (default: 'created_at').
     * @param string             $sort_order   'ASC' or 'DESC'.
     * @return array
     */
    public function getByFilters(
        $project_id = null,
        $status = null,
        $title = null,
        $project_type = null,
        $files = null,
        $sort_col = 'created_at',
        $sort_order = 'ASC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = [
            'project_id'   => $project_id,
            'status'       => $status,
            'title'        => $title,
            'project_type'=> $project_type,
            'files'        => $files
        ];

        foreach ($filters as $col => $val) {
            if ($val !== null) {
                if (is_array($val)) {
                    $safe = array_map(fn($v) => in_array($col, ['project_id','status']) ? intval($v) : "'" . $this->filter($v) . "'", $val);
                    $sql .= " AND {$col} IN (" . implode(',', $safe) . ")";
                } else {
                    $safeVal = in_array($col, ['project_id','status']) ? intval($val) : "'" . $this->filter($val) . "'";
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