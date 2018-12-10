<?php
namespace Lucinda\Query;

require_once("AbstractClause.php");
require_once("JoinOperator.php");
require_once("Alias.php");
require_once("Condition.php");

/**
 * Encapsulates SQL JOIN clause
 */
class Join extends AbstractClause {
	protected $joinType;
	protected $table;
	protected $whereClause;

	/**
	 * Creates a join clause object
	 *
	 * @param string $tableName
     * @param string $tableAlias
     * @param JoinOperator $joinType
	 */
	public function __construct($tableName, $tableAlias = null, $joinType=JoinOperator::INNER) {
		$this->table = $tableAlias?new Alias($tableName, $tableAlias):$tableName;
        $this->joinType = $joinType;
	}

	/**
	 * Activates ON clause consisting of simple conditions (using a single logical operator).
	 * EXAMPLE: a=1 AND b=2 AND c=3 AND D=4
	 *
     * @param string[string] $condition
	 * @param LogicalOperator $logicalOperator
	 * @return Condition
	 */
	public function on($condition = array(), $logicalOperator=LogicalOperator::_AND_) {
		$whereClause = new Condition($condition, $logicalOperator);
		$this->whereClause=$whereClause;
		return $whereClause;
	}

	/**
	 * (non-PHPdoc)
	 * @see AbstractClause::toString()
	 */
	public function toString() {
		return $this->joinType." ".($this->table instanceof Alias?$this->table->toString():$this->table).($this->whereClause?" ON ".$this->whereClause->toString():"");
	}
}