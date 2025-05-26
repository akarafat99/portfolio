<?php
include_once 'DatabaseConnector.php';

class Research
{
    public $conn;
    public $db;
    public $table = null;

    public $research_id     = 0;
    public $status          = 1;
    public $research_type   = "";
    public $research_title  = "";
    public $abstract        = "";    // Research abstract
    public $published_date  = "";
    public $accepted_date   = "";
    public $journal_name    = "";
    public $doi             = "";
    public $isbn            = "";
    public $issn            = "";
    public $publisher       = "";
    public $file_url        = "";
    public $website_link    = "";
    public $files           = "";    // Comma-separated file IDs
    public $created_at      = "";
    public $updated_at      = "";

    /**
     * Research constructor.
     *
     * @param string|null $tableName Optional custom table name.
     */
    public function __construct($tableName = null)
    {
        $this->ensureConnection();
        $this->table = $tableName ?? 'tbl_research';
    }

    /**
     * Ensure there is an active database connection.
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
     */
    public function disconnect()
    {
        if ($this->db) {
            $this->db->disconnect();
        }
        $this->conn = null;
    }

    /**
     * Create minimal table with only primary key.
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            research_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
    }

    /**
     * Add missing columns to the table.
     * @param int[] $indexes Optional list of 1-based column indexes.
     */
    public function alterTableAddColumns(array $indexes = [])
    {
        $columns = [
            1  => ['name'=>'status',          'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS status INT DEFAULT 0'],
            2  => ['name'=>'research_type',   'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS research_type TEXT'],
            3  => ['name'=>'research_title',  'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS research_title TEXT'],
            4  => ['name'=>'abstract',        'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS abstract TEXT'],
            5  => ['name'=>'published_date',  'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS published_date DATE'],
            6  => ['name'=>'accepted_date',   'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS accepted_date DATE'],
            7  => ['name'=>'journal_name',    'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS journal_name TEXT'],
            8  => ['name'=>'doi',             'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS doi TEXT'],
            9  => ['name'=>'isbn',            'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS isbn TEXT'],
            10 => ['name'=>'issn',            'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS issn TEXT'],
            11 => ['name'=>'publisher',       'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS publisher TEXT'],
            12 => ['name'=>'file_url',        'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS file_url TEXT'],
            13 => ['name'=>'website_link',    'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS website_link TEXT'],
            14 => ['name'=>'files',           'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS files TEXT'],
            15 => ['name'=>'created_at',      'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            16 => ['name'=>'updated_at',      'sql'=>'ALTER TABLE '.$this->table.' ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
        ];
        $toRun = empty($indexes) ? array_keys($columns) : array_intersect(array_keys($columns), $indexes);
        foreach ($toRun as $i) {
            $col  = $columns[$i]['name'];
            $sql  = $columns[$i]['sql'];
            $lbl  = "#{$i} ({$col})";
            if (mysqli_query($this->conn, $sql)) {
                echo "✅ {$lbl} added.<br>";
            } else {
                echo "ℹ️ {$lbl} exists or error: ".mysqli_error($this->conn)."<br>";
            }
        }
    }

    /**
     * Escape for safe SQL.
     */
    private function filter($val)
    {
        return mysqli_real_escape_string($this->conn, $val);
    }

    /**
     * Insert a new research record.
     */
    public function insert()
    {
        $sql = "INSERT INTO {$this->table} (
            status, research_type, research_title, abstract,
            published_date, accepted_date, journal_name,
            doi, isbn, issn, publisher, file_url, website_link, files
        ) VALUES (
            {$this->status},
            '".$this->filter($this->research_type) ."',
            '".$this->filter($this->research_title)."',
            '".$this->filter($this->abstract)."',
            '".$this->filter($this->published_date)."',
            '".$this->filter($this->accepted_date)."',
            '".$this->filter($this->journal_name)."',
            '".$this->filter($this->doi)."',
            '".$this->filter($this->isbn)."',
            '".$this->filter($this->issn)."',
            '".$this->filter($this->publisher)."',
            '".$this->filter($this->file_url)."',
            '".$this->filter($this->website_link)."',
            '".$this->filter($this->files)."'
        )";
        return mysqli_query($this->conn,$sql) ? mysqli_insert_id($this->conn) : 0;
    }

    /**
     * Update existing research.
     */
    public function update()
    {
        $sql = "UPDATE {$this->table} SET
            status          = {$this->status},
            research_type   = '".$this->filter($this->research_type)."',
            research_title  = '".$this->filter($this->research_title)."',
            abstract        = '".$this->filter($this->abstract)."',
            published_date  = '".$this->filter($this->published_date)."',
            accepted_date   = '".$this->filter($this->accepted_date)."',
            journal_name    = '".$this->filter($this->journal_name)."',
            doi             = '".$this->filter($this->doi)."',
            isbn            = '".$this->filter($this->isbn)."',
            issn            = '".$this->filter($this->issn)."',
            publisher       = '".$this->filter($this->publisher)."',
            file_url        = '".$this->filter($this->file_url)."',
            website_link    = '".$this->filter($this->website_link)."',
            files           = '".$this->filter($this->files)."'
          WHERE research_id = {$this->research_id}";
        return mysqli_query($this->conn,$sql);
    }

    /**
     * Change status of a research.
     */
    public function updateStatus($id, $status=1)
    {
        $sql = "UPDATE {$this->table} SET status = {$status} WHERE research_id = {$id}";
        return mysqli_query($this->conn,$sql);
    }

    /**
     * Map DB row to object properties.
     */
    public function setProperties($row)
    {
        foreach ($row as $k=>$v) {
            if (property_exists($this,$k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Retrieve records by filters.
     */
    public function getByFilters(
        $research_id=null,
        $status=null,
        $research_type=null,
        $journal_name=null,
        $publisher=null,
        $sort_col='published_date',
        $sort_order='DESC'
    ) {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $filters = compact('research_id','status','research_type','journal_name','publisher');
        foreach ($filters as $col=>$val) {
            if ($val!==null) {
                if (is_array($val)) {
                    $safe = array_map(fn($x)=>is_numeric($x)?intval($x):"'".$this->filter($x)."'", $val);
                    $sql .= " AND {$col} IN(".implode(',',$safe).")";
                } else {
                    $safe = is_numeric($val)?intval($val):"'".$this->filter($val)."'";
                    $sql .= " AND {$col}={$safe}";
                }
            }
        }
        $sql .= " ORDER BY {$sort_col} {$sort_order}";
        $res = mysqli_query($this->conn,$sql);
        if (!$res || mysqli_num_rows($res)==0) return [];
        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        if (count($rows)==1) $this->setProperties($rows[0]);
        return $rows;
    }
}
?>

<!-- end -->