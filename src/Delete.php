<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("AbstractStatement.php");
require_once("clauses/Condition.php");

/**
 * Encapsulates SQL statement: DELETE FROM {TABLE} WHERE {CONDITION}
 */
class Delete extends AbstractStatement {
	protected $where;
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

	public function where($condition=array(), $logicalOperator=LogicalOperator::_AND_) {
		$where = new Condition($condition, $logicalOperator);
		$this->where=$where;
		return $where;
	}
	
	public function toString() {
		return "DELETE FROM ".$this->table.
            ($this->where?"\r\n"."WHERE ".$this->where->toString():"");
	}
}