<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("AbstractStatement.php");
require_once("clauses/Columns.php");
require_once("clauses/Row.php");

/**
 * Encapsulates SQL statement: INSERT INTO {TABLE} ({COLUMNS}) VALUES ({ROW}), ...
 */
class Insert extends AbstractStatement {
	protected $columns;
	protected $rows = array();
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

	public function columns($columns = array()) {
		$fields = new Columns($columns);
		$this->columns = $fields;
		return $fields;
	}

	public function values($updates = array()) {
		$row = new Row($updates);
		$this->rows[] = $row;
		return $row;
	}
	
	public function toString() {
		if(!$this->columns) throw new Exception("running columns() method is mandatory");
        if(!$this->rows) throw new Exception("running values() is mandatory");

		$output = "INSERT INTO ".$this->table." (".$this->columns->toString().") VALUES "."\r\n";
        foreach($this->rows as $row) {
            $output.=$row->toString().", ";
        }
		return  substr($output,0,-2);
	}
}