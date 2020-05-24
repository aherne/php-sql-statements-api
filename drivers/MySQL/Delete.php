<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Delete as DefaultDelete;

/**
 * Encapsulates MySQL statement: DELETE {IGNORE} FROM {TABLE} WHERE {CONDITION}
 */
class Delete extends DefaultDelete
{
    protected $isIgnore=false;

    /**
     * Sets statement as "IGNORE" (ignoring foreign key errors / duplicates)
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
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
