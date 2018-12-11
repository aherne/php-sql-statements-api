<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("Stringable.php");
require_once("clauses/Condition.php");

/**
 * Encapsulates SQL statement: DELETE FROM {TABLE} WHERE {CONDITION}
 */
class Delete implements Stringable {
	protected $where;
    protected $table;

    /**
     * @param string $table Name of table to delete from (including schema)
     */
    public function __construct($table) {
        $this->table = $table;
    }

    /**
     * Sets up WHERE clause.
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param LogicalOperator $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
	public function where($condition=array(), $logicalOperator=LogicalOperator::_AND_) {
		$where = new Condition($condition, $logicalOperator);
		$this->where=$where;
		return $where;
	}

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
	public function toString() {
		return "DELETE FROM ".$this->table.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
	}
}