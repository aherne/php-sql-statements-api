<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("Stringable.php");
require_once("clauses/Set.php");
require_once("clauses/Condition.php");

/**
 * Encapsulates SQL statement: UPDATE {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update implements Stringable {
	protected $set;
	protected $where;
    protected $table;

    /**
     * @param string $table Name of table to update (including schema)
     */
    public function __construct($table) {
        $this->table = $table;
    }

    /**
     * Sets up SET clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
	public function set($contents = array()) {
		$set = new Set($contents);
		$this->set = $set;
		return $set;
	}

    /**
     * Sets up WHERE clause.
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param LogicalOperator $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
    public function where($condition = array(), $logicalOperator=LogicalOperator::_AND_) {
        $where = new Condition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
	public function toString() {
		if(!$this->set) throw new Exception("running set() method is required");

		return	"UPDATE ".$this->table.
            "\r\n"."SET ".$this->set->toString().
            ($this->where?"\r\n"."WHERE ".$this->where->toString():"");
	}
}