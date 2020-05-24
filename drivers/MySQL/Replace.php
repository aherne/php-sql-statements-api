<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Insert;

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} ({COLUMNS}) VALUES ({ROW})
 */
class Replace extends Insert
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
            throw new Exception("running columns() method is mandatory");
        }
        if (empty($this->rows)) {
            throw new Exception("running values() is mandatory");
        }

        $output = "REPLACE INTO ".$this->table." (".$this->columns->toString().") VALUES "."\r\n";
        foreach ($this->rows as $row) {
            $output.=$row->toString().", ";
        }
        return substr($output, 0, -2);
    }
}
