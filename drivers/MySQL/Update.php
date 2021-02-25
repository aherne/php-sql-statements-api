<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Update as DefaultUpdate;
use Lucinda\Query\Clause\Condition;
use Lucinda\Query\Operator\Logical;

/**
 * Encapsulates MySQL statement: UPDATE {IGNORE} {TABLE} SET {SET} WHERE {CONDITION}
 */
class Update extends DefaultUpdate
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
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is required");
        }

        return "UPDATE ".($this->isIgnore?"IGNORE ":"").$this->table.
            "\r\n"."SET ".$this->set->toString().
            ($this->where && !$this->where->isEmpty()?"\r\n"."WHERE ".$this->where->toString():"");
    }
}
