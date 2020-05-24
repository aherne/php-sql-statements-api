<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\InsertSelect;

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class ReplaceSelect extends InsertSelect
{
    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function toString(): string
    {
        if (!$this->columns || $this->columns->isEmpty()) {
            throw new Exception("running columns() method is required!");
        }
        if (!$this->select) {
            throw new Exception("running select() method is required!");
        }

        return  "REPLACE INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString();
    }
}
