<?php

namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class ReplaceSelect extends \Lucinda\Query\InsertSelect
{
    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        return "REPLACE INTO ".$this->table." (".$this->getColumns().")"."\r\n".$this->getSelect();
    }
}
