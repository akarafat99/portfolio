<?php
include_once 'DatabaseConnector.php';

class Lab
{
    public $conn;
    public $db;
    public $table = null;

    public $lab_id              = 0;
    public $status              = 0;
    public $lab_title           = "";
    public $lab_about           = "";
    public $lab_outcome         = "";
    public $lab_head_name       = "";
    public $lab_head_details    = "";
    public $lab_members_name    = "";
    public $lab_members_details = "";
    public $files               = "";  // comma-separated IDs
    public $created_at          = "";
    public $modified_at         = "";

    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_lab';
    }

    public function ensureConnection()
    {
        if (!$this->conn) {
            $this->db = new DatabaseConnector();
            $this->db->connect();
            $this->conn = $this->db->getConnection();
        }
    }

    public function disconnect()
    {
        if ($this->db) {
            $this->db->disconnect();
        }
        $this->conn = null;
    }

    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            lab_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    public function alterTableAddColumns(array $indexes = [])
    {
        $cols = [
            1 => ['status',              "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS status INT DEFAULT 0"],
            2 => ['lab_title',           "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_title TEXT"],
            3 => ['lab_about',           "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_about TEXT"],
            4 => ['lab_outcome',         "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_outcome TEXT"],
            5 => ['lab_head_name',       "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_head_name TEXT"],
            6 => ['lab_head_details',    "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_head_details TEXT"],
            7 => ['lab_members_name',    "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_members_name TEXT"],
            8 => ['lab_members_details', "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS lab_members_details TEXT"],
            9 => ['files',               "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS files TEXT"],
            10=> ['created_at',          "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            11=> ['modified_at',         "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
        ];
        $toRun = empty($indexes) ? array_keys($cols) : array_intersect(array_keys($cols), $indexes);
        foreach ($toRun as $i) {
            $res = mysqli_query($this->conn, $cols[$i][1]);
            if (!$res) {
                echo "Error: " . mysqli_error($this->conn) . "<br>";
            } else {
                echo "Column {$cols[$i][0]} added successfully.<br>";
            }
        }
    }

    private function filter($val)
    {
        return mysqli_real_escape_string($this->conn, $val);
    }

    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, lab_title, lab_about, lab_outcome,
            lab_head_name, lab_head_details,
            lab_members_name, lab_members_details, files
        ) VALUES (
            {$this->status},
            '".$this->filter($this->lab_title)."',
            '".$this->filter($this->lab_about)."',
            '".$this->filter($this->lab_outcome)."',
            '".$this->filter($this->lab_head_name)."',
            '".$this->filter($this->lab_head_details)."',
            '".$this->filter($this->lab_members_name)."',
            '".$this->filter($this->lab_members_details)."',
            '".$this->filter($this->files)."'
        )";
        if (mysqli_query($this->conn, $sql)) {
            $this->lab_id = mysqli_insert_id($this->conn);
            return $this->lab_id;
        }
        return 0;
    }

    public function update()
    {
        $sql = "UPDATE {$this->table} SET 
            status = {$this->status},
            lab_title = '".$this->filter($this->lab_title)."',
            lab_about = '".$this->filter($this->lab_about)."',
            lab_outcome = '".$this->filter($this->lab_outcome)."',
            lab_head_name = '".$this->filter($this->lab_head_name)."',
            lab_head_details = '".$this->filter($this->lab_head_details)."',
            lab_members_name = '".$this->filter($this->lab_members_name)."',
            lab_members_details = '".$this->filter($this->lab_members_details)."',
            files = '".$this->filter($this->files)."'
          WHERE lab_id = {$this->lab_id}";
        return mysqli_query($this->conn, $sql);
    }

    public function updateStatus($lab_id, $status = 1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE lab_id = {$lab_id}";
        return mysqli_query($this->conn, $sql);
    }

    public function setProperties(array $row)
    {
        foreach ($row as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Retrieve records by lab_id and status, sorted
     * @param int|int[]|null $lab_id
     * @param int|int[]|null $status
     * @param string $sort_col
     * @param string $sort_order
     * @return array
     */
    public function getByFilters(
        $lab_id     = null,
        $status     = null,
        $sort_col   = 'created_at',
        $sort_order = 'DESC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        if ($lab_id !== null) {
            if (is_array($lab_id)) {
                $ids = implode(',', array_map('intval', $lab_id));
                $sql .= " AND lab_id IN ({$ids})";
            } else {
                $sql .= " AND lab_id = " . intval($lab_id);
            }
        }
        if ($status !== null) {
            if (is_array($status)) {
                $s = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ({$s})";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }
        $sql .= " ORDER BY {$sort_col} {$sort_order}";

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