<?php

namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Select as DefaultSelect;
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Vendor\MySQL\Clause\Condition as MySQLCondition;

/**
 * Encapsulates MySQL statement:
 * SELECT {FIELDS}
 * FROM {TABLE}
 * {TYPE} JOIN ON {CONDITION}
 * ...
 * WHERE {CONDITION}
 * GROUP BY {COLUMNS}
 * HAVING {CONDITION}
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class Select extends DefaultSelect
{
    private bool $calcFoundRows = false;
    private bool $straightJoin = false;

    /**
     * Appends a SQL_CALC_FOUND_ROWS option to SELECT
     */
    public function setCalcFoundRows(): void
    {
        $this->calcFoundRows = true;
    }

    /**
     * Appends a STRAIGHT_JOIN option to SELECT
     */
    public function setStraightJoin(): void
    {
        $this->straightJoin = true;
    }

    /**
     * Gets query to retrieve found rows after a SELECT with SQL_CALC_FOUND_ROWS has ran
     *
     * @return string
     */
    public function getCalcFoundRows(): string
    {
        return "SELECT FOUND_ROWS()";
    }

    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Select::where()
     */
    public function where(array $condition=[], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new MySQLCondition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Select::getOptions()
     */
    protected function getOptions(): string
    {
        return
            ($this->isDistinct ? " DISTINCT" : "").
            ($this->straightJoin ? " STRAIGHT_JOIN" : "").
            ($this->calcFoundRows ? " SQL_CALC_FOUND_ROWS" : "");
    }
}
