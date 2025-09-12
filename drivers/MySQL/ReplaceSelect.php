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
    public function toString(): string
    {
        if (!$this->columns || $this->columns->isEmpty()) {
            throw new Exception("running columns() method is required!");
        }
        if (!$this->select) {
            throw new Exception("running select() method is required!");
        }

        $output = "";
        if ($this->with) {
            $output = $this->with->toString()."\r\n";
        }
        $output .= "REPLACE INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString();
        return $output;
    }
}
