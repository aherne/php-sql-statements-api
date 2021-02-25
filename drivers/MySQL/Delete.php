<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Delete as DefaultDelete;
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical;

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class Delete extends DefaultDelete
{
    protected $isIgnore=false;

    /**
     * Sets statement as IGNORE, ignoring foreign key errors and duplicates
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
    }
    
    /**
     * {@inheritDoc}
     * @see \Lucinda\Query\Update::where()
     */
    public function where(array $condition=[], string $logicalOperator=Logical::_AND_): Condition
    {
        $where = new \Lucinda\Query\Vendor\MySQL\Clause\Condition($condition, $logicalOperator);
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
        return "DELETE ".($this->isIgnore?"IGNORE":"")." FROM ".$this->table.
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
    }
}
