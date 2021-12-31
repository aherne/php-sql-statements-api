<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Set;
use Lucinda\Query\Operator\Logical AS LogicalOperator;
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
     * @param LogicalOperator $logicalOperator Enum holding operator that will link conditions in group (default: AND)
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
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is required");
        }

        return	"UPDATE ".$this->table.
            "\r\n"."SET ".$this->set.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where:"");
    }
}
