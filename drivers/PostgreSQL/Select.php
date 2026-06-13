<?php

namespace Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Select as DefaultSelect;
use Lucinda\Query\Vendor\PostgreSQL\Clause\Condition as PostgreSQLCondition;

/**
 * Encapsulates PostgreSQL SELECT statement on top of SQL standard SELECT
 */
final class Select extends DefaultSelect
{
    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Select::where()
     */
    public function where(array $condition=[], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new PostgreSQLCondition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Select::having()
     */
    public function having(array $condition=[], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new PostgreSQLCondition($condition, $logicalOperator);
        $this->having=$where;
        return $where;
    }
}
