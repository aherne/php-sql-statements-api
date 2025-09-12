<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Clause\With;
use Lucinda\Query\Operator\Logical;

/**
 * Encapsulates SQL statement: DELETE FROM {TABLE} WHERE {CONDITION}
 */
class Delete implements Stringable
{
    protected $with;
    protected $where;
    protected $table;

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
     * Sets a WITH common table expressions (CTE) clause
     *
     * @param bool $isRecursive
     * @return With Object to set WITH clauses on.
     */
    public function with(bool $isRecursive = false): With
    {
        $with = new With($isRecursive);
        $this->with = $with;
        return $with;
    }

    /**
     * Sets up WHERE clause.
     *
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
    public function where(array $condition=[], string $logicalOperator=Logical::_AND_): Condition
    {
        $where = new Condition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        if ($this->with) {
            $output = $this->with->toString()."\r\n";
        }
        $output .= "DELETE FROM ".$this->table.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
        return $output;
    }
}
