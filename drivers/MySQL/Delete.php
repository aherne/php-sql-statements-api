<?php

namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Clause\Join;
use Lucinda\Query\Delete as DefaultDelete;
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Exception;
use Lucinda\Query\Operator\Join as JoinOperator;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Vendor\MySQL\Clause\Condition as MySQLCondition;

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class Delete extends DefaultDelete
{
    protected bool $isIgnore=false;
    protected array $joins=[];

    /**
     * Sets statement as IGNORE, ignoring foreign key errors and duplicates
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
    }

    /**
     * Adds a LEFT JOIN statement
     *
     * @param string $tableName Name of table to join with (including schema)
     * @return Join Object to set join conditions on.
     */
    public function joinLeft(string $tableName): Join
    {
        $join = new Join($tableName, "", JoinOperator::LEFT);
        $this->joins[]=$join;
        return $join;
    }

    /**
     * Adds a RIGHT JOIN statement
     *
     * @param string $tableName Name of table to join with (including schema)
     * @return Join Object to set join conditions on.
     */
    public function joinRight(string $tableName): Join
    {
        $join = new Join($tableName, "", JoinOperator::RIGHT);
        $this->joins[]=$join;
        return $join;
    }

    /**
     * Adds a INNER JOIN statement
     *
     * @param string $tableName Name of table to join with (including schema)
     * @return Join Object to set join conditions on.
     */
    public function joinInner(string $tableName): Join
    {
        $join = new Join($tableName, "", JoinOperator::INNER);
        $this->joins[]=$join;
        return $join;
    }

    /**
     * Adds a CROSS JOIN statement
     *
     * @param string $tableName Name of table to join with (including schema)
     * @return Join Object to set join conditions on.
     */
    public function joinCross(string $tableName): Join
    {
        $join = new Join($tableName, "", JoinOperator::CROSS);
        $this->joins[]=$join;
        return $join;
    }

    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Update::where()
     */
    public function where(array $condition=[], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new MySQLCondition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        $output = "";
        if ($this->with) {
            $output = $this->with."\r\n";
        }
        $output .= "DELETE ".($this->isIgnore ? "IGNORE" : "")." FROM ".$this->table;
        if (sizeof($this->joins)>0) {
            foreach ($this->joins as $join) {
                $output .= "\r\n".$join;
            }
        }
        $output .= ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where:"");
        return $output;
    }
}
