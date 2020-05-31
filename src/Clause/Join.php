<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;
use Lucinda\Query\Operator\Join as JoinOperator;
use Lucinda\Query\Operator\Logical;

/**
 * Encapsulates SQL JOIN clause
 */
class Join implements Stringable
{
    protected $joinType;
    protected $table;
    protected $whereClause;

    /**
     * Creates a join clause object
     *
     * @param string $tableName Name of table to join.
     * @param string $tableAlias Optional alias of table to join.
     * @param JoinOperator $joinType  Enum holding join operator that will be used in statement (default: INNER)
     */
    public function __construct(string $tableName, string $tableAlias = "", string $joinType=JoinOperator::INNER)
    {
        $this->table = $tableAlias?new Alias($tableName, $tableAlias):$tableName;
        $this->joinType = $joinType;
    }

    /**
     * Activates ON clause consisting of simple conditions (using a single logical operator).
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Condition to setup.
     */
    public function on(array $condition = [], string $logicalOperator=Logical::_AND_): Condition
    {
        $whereClause = new Condition($condition, $logicalOperator);
        $this->whereClause=$whereClause;
        return $whereClause;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        return $this->joinType." ".$this->table.($this->whereClause && !$this->whereClause->isEmpty()?" ON ".$this->whereClause->toString():"");
    }
}
