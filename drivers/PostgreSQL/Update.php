<?php

namespace Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Clause\Fields;
use Lucinda\Query\Exception;
use Lucinda\Query\Operator\Logical as LogicalOperator;
use Lucinda\Query\Update as DefaultUpdate;
use Lucinda\Query\Vendor\PostgreSQL\Clause\Condition as PostgreSQLCondition;

/**
 * Encapsulates PostgreSQL UPDATE statement on top of SQL standard UPDATE
 */
final class Update extends DefaultUpdate
{
    protected ?Fields $returning = null;

    /**
     * {@inheritDoc}
     *
     * @see \Lucinda\Query\Update::where()
     */
    public function where(array $condition = [], LogicalOperator $logicalOperator=LogicalOperator::_AND_): Condition
    {
        $where = new PostgreSQLCondition($condition, $logicalOperator);
        $this->where=$where;
        return $where;
    }

    /**
     * Sets RETURNING clause.
     *
     * @param string[] $columns Sets list of expressions directly
     * @return Fields Object to add further expressions on.
     */
    public function returning(array $columns = []): Fields
    {
        $fields = new Fields($columns);
        $this->returning = $fields;
        return $fields;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception
     */
    public function __toString(): string
    {
        return parent::__toString().$this->getReturning();
    }

    protected function getReturning(): string
    {
        return ($this->returning && !$this->returning->isEmpty() ? "\r\nRETURNING ".$this->returning : "");
    }
}
