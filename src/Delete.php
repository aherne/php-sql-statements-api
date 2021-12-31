<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical;

/**
 * Encapsulates SQL statement: DELETE FROM {TABLE} WHERE {CONDITION}
 */
class Delete implements \Stringable
{
    protected ?Condition $where = null;
    protected string $table;

    /**
     * Constructs a DELETE statement based on table name
     * 
     * @param string $table Name of table to delete from (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Sets up WHERE clause.
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
    public function where(array $condition=[], Logical $logicalOperator=Logical::_AND_): Condition
    {
        $where = new Condition($condition, $logicalOperator);
        $this->where = $where;
        return $where;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        return "DELETE FROM ".$this->table.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where:"");
    }
}
