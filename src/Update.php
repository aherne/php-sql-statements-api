<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Set;
use Lucinda\Query\Clause\With;
use Lucinda\Query\Operator\Logical;
use Lucinda\Query\Clause\Condition;

/**
 * Encapsulates SQL statement: UPDATE {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update implements Stringable
{
    protected $with;
    protected $set;
    protected $where;
    protected $table;

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
     * Sets up SET clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
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
     * @param string[string] $condition Sets condition group directly when conditions are all of equals type
     * @param Logical $logicalOperator Enum holding operator that will link conditions in group (default: AND)
     * @return Condition Object to set further conditions on.
     */
    public function where(array $condition = [], string $logicalOperator=Logical::_AND_): Condition
    {
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
    public function toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is required");
        }

        $output = "";
        if ($this->with) {
            $output = $this->with->toString()."\r\n";
        }
        $output .= "UPDATE ".$this->table.
            "\r\n"."SET ".$this->set->toString().
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
        return $output;
    }
}
