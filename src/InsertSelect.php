<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("Select.php");

/**
 * Encapsulates SQL statement: INSERT INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class InsertSelect extends AbstractStatement {
	protected $columns;
	protected $select;
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

    public function columns($columns = array()) {
        $fields = new Columns($columns);
        $this->columns = $fields;
        return $fields;
    }
	
	public function select(AbstractStatement $select) {
		$this->select=$select;
		return $select;
	}
	
	public function toString() {
        if(!$this->columns) throw new Exception("running columns() method is required!");
		if(!$this->select) throw new Exception("running select() method is required!");

		return  "INSERT INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
				$this->select->toString();
	}
}