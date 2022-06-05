<?php

namespace Lucinda\Query;

use Lucinda\Query\Clause\Set;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Clause\Condition;

/**
 * Encapsulates SQL statement: UPDATE {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update implements \Stringable
{
    protected ?Set $set = null;
    protected ?Condition $where = null;
    protected string $table;

    /**
     * Constructs a UPDATE statement based on table name
     *
     * @param string $table Name of table to update (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Sets up SET clause.
     *
     * @param  array<string,string> $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
    public function set(array $contents = []): Set
    {
        $set = new Set($contents);
        $this->set = $set;
        return $set;
    }

    /**
     * Sets up WHERE clause.
     *
     * @param  array<string,string> $condition       Sets condition group directly when conditions are all of equals type
     * @param  LogicalOperator      $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
    public function where(array $condition = [], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new Condition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception
     */
    public function __toString(): string
    {
        return "UPDATE ".$this->table.$this->getSet().$this->getWhere();
    }

    /**
     * Converts SET clause set by user to string
     *
     * @return string
     * @throws Exception
     */
    protected function getSet(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is required");
        }
        return "\r\nSET ".$this->set;
    }

    /**
     * Converts WHERE clause set by user (if any) to string
     *
     * @return string
     */
    protected function getWhere(): string
    {
        return ($this->where && !$this->where->isEmpty() ? "\r\nWHERE ".$this->where : "");
    }
}
