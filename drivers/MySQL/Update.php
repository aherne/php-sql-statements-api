<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Update as DefaultUpdate;

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
