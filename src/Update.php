<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("AbstractStatement.php");
require_once("clauses/Set.php");
require_once("clauses/Condition.php");

/**
 * Encapsulates SQL statement: UPDATE {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update extends AbstractStatement {
	protected $set;
	protected $where;
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

	public function set($contents = array()) {
		$set = new Set($contents);
		$this->set = $set;
		return $set;
	}

    public function where($condition = array(), $logicalOperator=LogicalOperator::_AND_) {
        $where = new Condition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

	public function toString() {
		if(!$this->set) throw new Exception("running set() method is required");

		return	"UPDATE ".$this->table.
            "\r\n"."SET ".$this->set->toString().
            ($this->where?"\r\n"."WHERE ".$this->where->toString():"");
	}
}