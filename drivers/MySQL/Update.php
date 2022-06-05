<?php

namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Update as DefaultUpdate;
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Vendor\MySQL\Clause\Condition as MySQLCondition;

/**
 * Encapsulates MySQL statement: UPDATE {IGNORE} {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update extends DefaultUpdate
{
    protected bool $isIgnore=false;

    /**
     * Sets statement as IGNORE, ignoring foreign key errors and duplicates
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
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
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        return "UPDATE".($this->isIgnore ? " IGNORE" : "")." ".$this->table.$this->getSet().$this->getWhere();
    }
}
